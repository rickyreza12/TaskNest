# Use smallest official RabbitMQ base
FROM rabbitmq:3.13-alpine

# Install only the Management Plugin manually
RUN rabbitmq-plugins enable --offline rabbitmq_management

# Expose ports
EXPOSE 5672 15672

# Start RabbitMQ server
CMD ["rabbitmq-server"]
