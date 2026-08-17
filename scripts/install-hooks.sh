#!/bin/sh
set -e

root=$(git rev-parse --show-toplevel)
hook="$root/.git/hooks/pre-commit"

cat > "$hook" <<'HOOK'
#!/bin/sh
set -e

root=$(git rev-parse --show-toplevel)
script="$root/scripts/strip-ai-marks.py"

[ -f "$script" ] || exit 0
command -v python3 >/dev/null 2>&1 || exit 0

exec python3 "$script"
HOOK

chmod +x "$hook"
echo "installed $hook"
