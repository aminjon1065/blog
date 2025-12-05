# docker/node/Dockerfile
FROM node:20-alpine AS base

WORKDIR /app

# Депенденции
COPY frontend/package.json frontend/package-lock.json* ./
RUN npm ci

# Сборка (для dev можно не собирать, но в проде — обязательно)
COPY frontend .
RUN npm run build

# Продакшен раннер
FROM node:20-alpine AS production
WORKDIR /app

ENV NODE_ENV=production

COPY --from=base /app/.next ./.next
COPY --from=base /app/public ./public
COPY --from=base /app/package.json ./
COPY --from=base /app/node_modules ./node_modules

EXPOSE 3000
CMD ["npm", "start"]