#!/bin/bash

# Funktion zur Anzeige der Hilfe
show_help() {
  echo "Usage: ./update [-major | -minor | -patch]"
  echo ""
  echo "  -major     Erhöht die Major-Version (x.0.0)"
  echo "  -minor     Erhöht die Minor-Version (x.y.0)"
  echo "  -patch     (Standard) Erhöht die Patch-Version (x.y.z)"
}

# Standard: patch erhöhen
INCREMENT="patch"

# Flag-Auswertung
case "$1" in
  -major)
    INCREMENT="major"
    ;;
  -minor)
    INCREMENT="minor"
    ;;
  -patch|"")
    INCREMENT="patch"
    ;;
  -h|--help)
    show_help
    exit 0
    ;;
  *)
    echo "Unbekannte Option: $1"
    show_help
    exit 1
    ;;
esac

# Holen des aktuellen Tags
TAG=$(git describe --tags | tr -d '[:space:]')

# Wenn wir auf einem sauberen Tag sind und nur Patch erhöhen wollen, abbrechen
if [[ "$TAG" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ && "$INCREMENT" == "patch" ]]; then
  echo "Bereits auf dem neuesten Patch-Level ($TAG). Kein Update nötig."
  exit 0
fi

# Extrahieren der Basisversion
BASE_VERSION=$(echo "$TAG" | cut -d '-' -f 1)

# Sicherstellen, dass BASE_VERSION ein valider Semver ist
if ! [[ "$BASE_VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "❌ Ungültige Basisversion erkannt: '$BASE_VERSION'"
  exit 1
fi

# Debug-Ausgabe
echo "Basisversion erkannt: $BASE_VERSION"

IFS='.' read -r MAJOR MINOR PATCH <<< "$BASE_VERSION"

# Version erhöhen
case "$INCREMENT" in
  major)
    MAJOR=$((MAJOR + 1))
    MINOR=0
    PATCH=0
    ;;
  minor)
    MINOR=$((MINOR + 1))
    PATCH=0
    ;;
  patch)
    PATCH=$((PATCH + 1))
    ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH"

echo "Aktuelle Version: $TAG"
echo "Neuer Tag wird erstellt: $NEW_VERSION"

# Neuen Tag setzen und pushen
git tag "$NEW_VERSION"
git push --tags

echo "Update abgeschlossen auf $NEW_VERSION"
