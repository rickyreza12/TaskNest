#Frontend Dockerfile
FROM node:18-slim

#set working directory
WORKDIR /app

#Copy package.json and package-lock.json 
COPY package*.json ./

#install alpine packages
# RUN apk add --no-cache bash make gcc g++ python3

#install dependencies
RUN [ -f package.json ] && npm install || echo "No package.json found, skipping npm install"

#Copy rest of the app
COPY . .

#Expose Vite default port
EXPOSE 5173

# Remove CMD because we control with docker-compose command: sh
# CMD ["npm", "run", "dev", "--", "--host"]
