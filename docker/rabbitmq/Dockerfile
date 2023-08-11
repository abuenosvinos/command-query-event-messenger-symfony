FROM rabbitmq:3.8-management

RUN apt-get -o Acquire::Check-Date=false update && apt-get install -y curl

RUN curl -L https://github.com/rabbitmq/rabbitmq-delayed-message-exchange/releases/download/3.8.17/rabbitmq_delayed_message_exchange-3.8.17.8f537ac.ez > $RABBITMQ_HOME/plugins/rabbitmq_delayed_message_exchange-3.8.17.ez

RUN chown rabbitmq:rabbitmq $RABBITMQ_HOME/plugins/rabbitmq_delayed_message_exchange-3.8.17.ez

RUN rabbitmq-plugins enable rabbitmq_delayed_message_exchange
