cd /var/www/html

if [ ! -d "./node_modules" ] || [ -z "$( ls -A './node_modules' )" ]; then
    npm install
fi

npm run dev