# Etape 1: Build
FROM node:20-alpine as build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etape 2: Nginx bach iservi l'Vue.js
FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
# Ila knti khedam b Vue Router (History mode), khassk tzid config dyal Nginx hna
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]