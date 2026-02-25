#!/bin/bash

# Configuración de variables
HOST="0.0.0.0"
PORT=8000
DOC_ROOT="public"

echo "------------------------------------------------"
echo "🚀 Iniciando Servidor de Desarrollo Symfony"
echo "📍 Dirección: http://localhost:$PORT"
echo "📂 Root: $DOC_ROOT"
echo "------------------------------------------------"

# Verificar si el puerto ya está en uso
if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null ; then
    echo "⚠️ Error: El puerto $PORT ya está siendo utilizado."
    echo "Intenta cerrarlo o cambiar el puerto en el script."
    exit 1
fi

# Ejecutar el comando de PHP
php -S $HOST:$PORT -t $DOC_ROOT
