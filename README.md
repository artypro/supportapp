# Laravel Support Ticket System

This project is a minimal Laravel-based application that allows users to submit support tickets via a web form and Telegram bot. It includes user authentication, ticket management, and optional Slack notifications for ticket submissions.

## Features

- User registration and login using Laravel Auth.
- Submit support tickets via:
    - Web form
    - Telegram bot interaction
- Admin receives Slack notifications when a ticket is submitted (optional).
- Support categories for ticket classification.

## Requirements

- PHP >= 8.2
- Composer
- Laravel >= 11.x
- A Telegram bot token
- Slack webhook URL (optional)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/artypro/supportapp.git
   cd supportapp
   ```

2. **Install dependencies:**

   ```bash
   composer install
   ```

3. **Set up the environment file:**

   Copy the `.env.example` to `.env` and update the necessary configurations, including database credentials, Telegram bot token, and Slack webhook URL.

   ```bash
   cp .env.example .env
   ```

4. **Generate the application key:**

   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed the database:**

   ```bash
   php artisan migrate --seed
   ```

6. **Set up the Telegram webhook:**

   Point your Telegram bot to the `/telegram/webhook` route of your application.

7. **Start the local development server:**

   ```bash
   php artisan serve
   ```

## Usage

- Access the application at `http://localhost:8000`.
- Register a new user or log in with existing credentials.
- Navigate to the Support section to submit a ticket via the web form or interact with the Telegram bot.

## Optional Features

- Implement file size validation via Telegram Bot API’s `getFile`.
- Archive incomplete tickets after 24 hours using Laravel Scheduler.
- Admin receives Slack notifications when a ticket is submitted.

## Environment Variables

Copy `.env.example` to `.env` and fill in the following required keys:

- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Database configuration.
- `TELEGRAM_BOT_TOKEN`: Your Telegram bot token (required for Telegram integration).
- `SLACK_WEBHOOK_URL`: Slack webhook URL (optional, for notifications).

Example:

```
TELEGRAM_BOT_TOKEN=your-telegram-bot-token
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/your/slack/webhook
```

Make sure to set these before running migrations or using the bot/webhook features.

## License

This project is open-source and available under the [MIT License](LICENSE).
