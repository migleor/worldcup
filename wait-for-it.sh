#!/bin/bash

# Parsea los argumentos de entrada
while [[ $# -gt 0 ]]; do
    case "$1" in
        --timeout)
            timeout="$2"
            shift 2
            ;;
        *:*)
            host="$1"
            port="${host#*:}"
            host="${host%:*}"
            shift
            ;;
        *)
            break
            ;;
    esac
done

# Establece valores predeterminados si no se proporcionan
timeout=${timeout:-15}
host=${host:-localhost}
port=${port:-80}

echo "Esperando que $host:$port esté disponible..."

# Intenta conectarse al servicio hasta que esté disponible o se alcance el tiempo de espera
while ! nc -z "$host" "$port"; do
    sleep 1
    timeout=$((timeout - 1))
    if [ $timeout -eq 0 ]; then
        echo "Tiempo de espera agotado. No se pudo conectar a $host:$port."
        exit 1
    fi
done

echo "$host:$port está disponible. Continuando..."
