Here is the updated `README.md` content.

The setup instructions and AWS deployment guide have been updated to reflect the new payment gateway variables and server requirements.

-----

# NaijaEdu-Pact

**NaijaEdu-Pact** is a digital platform designed to build a sustainable alumni donor pipeline for Nigerian tertiary institutions. It connects Nigerian alumni, particularly those in the diaspora, with their alma maters, enabling them to fund specific, vetted projects and re-engage with their university community.

The platform is built on three pillars: **Transparency** (donors see exactly what project they are funding), **Impact** (donors get live updates on project progress), and **Community** (a hub for networking, mentorship, and career services).

## ✨ Key Features

  * **Multi-Role Architecture:**
      * **Donors/Alumni:** Can register, find projects, donate, and access community features.
      * **Students:** Can register, access the job board, and request mentorship.
      * **University Admins:** Can manage their university's projects and campaigns.
      * **Super Admins:** Can vet projects and manage platform-wide settings.
  * **Project Discovery:** A public, searchable portal of all active university projects with progress bars.
  * **Pact Campaigns (Giving Days):** A module for universities to run high-energy, time-based fundraising events with live countdowns and stats.
  * **Pact Analytics (Donor Intelligence):** A background service that scores and segments donors based on their activity, helping admins identify high-potential donors.
  * **Student Lifecycle Engagement:**
      * **Job Board:** Alumni can post job opportunities for students from their alma mater.
      * **E-Mentoring:** Students can request mentorship from available alumni.
      * **Auto-Transition:** Students are automatically converted to alumni accounts upon their graduation year.
  * **Secure Payment Flow:** Integrated with Stripe Checkout to handle secure, real-time donations.

## 💻 Tech Stack

  * **Backend:** Laravel 12
  * **Frontend:** Vue.js (integrated via `laravel/ui`)
  * **Styling:** Materialize CSS
  * **API & Auth:** Laravel Passport
  * **Database:** MySQL
  * **Payments:** Stripe (primary), Paystack (planned)
  * **File Storage:** Local / Amazon S3
  * **Background Jobs:** Laravel Queues (Database Driver)
  * **Scheduled Tasks:** Laravel Scheduler

-----

## 🚀 Local Setup & Installation

Follow these steps to get the project running on your local machine.

### 1\. Prerequisites

  * [Laragon](https://laragon.org/download/) (or a similar AMP stack like XAMPP/MAMP)
  * [Composer](https://getcomposer.org/)
  * [Node.js & npm](https://nodejs.org/en)

### 2\. Clone the Repository

```bash
git clone https://github.com/your-username/naija-edu-pact.git
cd naija-edu-pact
```

### 3\. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 4\. Environment Configuration

```bash
# 1. Create your environment file
cp .env.example .env

# 2. Generate your application key
php artisan key:generate

# 3. Generate Passport keys for API auth
php artisan passport:install
```

### 5\. Configure your `.env` file

Open `.env` and set up your database, mail, and queue settings.

```ini
# --- App ---
APP_NAME="NaijaEdu-Pact"
APP_URL=http://naija-edu-pact.test
APP_DEBUG=true

# --- Database ---
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=naija_edu_pact
DB_USERNAME=root
DB_PASSWORD=

# --- Queue ---
QUEUE_CONNECTION=database

# --- Mail (for testing password resets, etc.) ---
MAIL_MAILER=log

# --- Storage ---
FILESYSTEM_DISK=public

# --- Payment Gateways (Stripe) ---
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# --- Currency Rates ---
# Set the NGN equivalent of 1 USD for payment validation
USD_TO_NGN_RATE=1450
```

### 6\. Run Migrations & Database Setup

```bash
# Run all database migrations and create tables
php artisan migrate:fresh

# (Optional) If you have seeders, run them
php artisan db:seed
```

### 7\. Link Storage

```bash
# This creates the 'public/storage' symlink
php artisan storage:link
```

-----

## 🏃 Running the Application

You need to run three separate processes in three separate terminals.

### Terminal 1: Serve the Application

```bash
# Use your Laragon/Valet URL or run:
php artisan serve
```

> Your app will be available at `http://localhost:8000`.

### Terminal 2: Compile Frontend Assets

```bash
# This will watch for changes to your Vue and CSS files
npm run dev
```

### Terminal 3: Run the Queue Worker

```bash
# This will process background jobs like analytics and emails
php artisan queue:work
```

### Running Scheduled Tasks

To test your scheduled commands (Analytics & Graduation) locally, run the scheduler:

```bash
php artisan schedule:work
```

-----

## ☁️ Hosting on AWS (Production Deployment)

Here is a guide for deploying your application to a scalable AWS infrastructure.

### AWS Services Used

  * **EC2:** For our application/web server (Linux).
  * **RDS:** For our managed MySQL database.
  * **S3:** For user file storage (project images, etc.).
  * **Supervisor:** To keep our queue worker running.
  * **Cron:** To run the Laravel Scheduler.

### Step 1: Set Up AWS IAM

1.  Create a new **IAM User** with "Programmatic access".
2.  Attach policies for `AmazonS3FullAccess` and `AmazonRDSFullAccess`.
3.  Save the **Access Key ID** and **Secret Access Key**. You will need these for your `.env` file.

### Step 2: Set Up Database (RDS)

1.  Go to the **RDS** service in your AWS Console.
2.  Create a new **MySQL** database instance.
3.  Configure its **Security Group** to only allow inbound MySQL traffic (Port 3306) from your EC2 instance's security group (which you'll create next).
4.  Note the **DB Endpoint URL**, **DB Name**, **Username**, and **Password**.

### Step 3: Set Up File Storage (S3)

1.  Go to the **S3** service.
2.  Create a new **S3 Bucket** (e.g., `naija-edu-pact-uploads`).
3.  Go to the "Permissions" tab and edit the **Block public access** settings to uncheck "Block all public access".
4.  Add a **Bucket Policy** to make uploaded files publicly readable:
    ```json
    {
        "Version": "2012-10-17",
        "Statement": [
            {
                "Sid": "PublicReadGetObject",
                "Effect": "Allow",
                "Principal": "*",
                "Action": "s3:GetObject",
                "Resource": "arn:aws:s3:::YOUR-BUCKET-NAME/*"
            }
        ]
    }
    ```

### Step 4: Set Up Server (EC2)

1.  Go to the **EC2** service.
2.  Launch a new instance (e.g., **Ubuntu Server 22.04 LTS**).
3.  Select an instance type (e.g., `t3.small`).
4.  Create a **Security Group** that allows:
      * `SSH` (Port 22) from your IP address.
      * `HTTP` (Port 80) from anywhere.
      * `HTTPS` (Port 443) from anywhere.
5.  Launch the instance and download your `.pem` (key file).

### Step 5: Provision the Server

1.  SSH into your new EC2 instance: `ssh -i "keyfile.pem" ubuntu@YOUR_EC2_IP`
2.  **Install Nginx:**
    ```bash
    sudo apt update
    sudo apt install nginx
    ```
3.  **Install PHP 8.3 & Extensions:**
    ```bash
    sudo apt install software-properties-common
    sudo add-apt-repository ppa:ondrej/php
    sudo apt update
    sudo apt install php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip
    ```
4.  **Install Composer & Node.js:**
    ```bash
    # Install Composer (globally)
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

    # Install Node.js
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt-get install -y nodejs
    ```

### Step 6: Deploy Your Application

1.  **Clone the project** onto your server (e.g., in `/var/www/naija-edu-pact`).
    ```bash
    cd /var/www
    git clone https://github.com/your-username/naija-edu-pact.git
    ```
2.  **Set permissions:**
    ```bash
    sudo chown -R $USER:www-data /var/www/naija-edu-pact
    sudo chmod -R 775 /var/www/naija-edu-pact/storage
    sudo chmod -R 775 /var/www/naija-edu-pact/bootstrap/cache
    ```
3.  **Configure `.env` for Production:**
      * `cp .env.example .env`
      * `nano .env`
      * Fill in all your credentials:
          * `APP_ENV=production`
          * `APP_DEBUG=false`
          * `APP_URL=http://YOUR_DOMAIN_OR_IP`
          * `DB_HOST=` (Your RDS Endpoint)
          * `DB_DATABASE=...`
          * `DB_USERNAME=...`
          * `DB_PASSWORD=...`
          * `FILESYSTEM_DISK=s3`
          * `AWS_ACCESS_KEY_ID=` (Your IAM Key)
          * `AWS_SECRET_ACCESS_KEY=` (Your IAM Secret)
          * `AWS_DEFAULT_REGION=` (e.g., `us-east-1`)
          * `AWS_BUCKET=` (Your S3 bucket name)
          * `USD_TO_NGN_RATE=1450` (or your current rate)
4.  **Install & Build:**
    ```bash
    composer install --no-dev --optimize-autoloader
    npm install
    npm run build
    ```
5.  **Run Final Setup:**
    ```bash
    php artisan key:generate
    php artisan storage:link
    php artisan passport:install --force
    php artisan migrate --force
    php artisan config:cache
    php artisan route:cache
    ```

### Step 7: Configure Nginx

1.  Create a new Nginx config file: `sudo nano /etc/nginx/sites-available/naija-edu-pact`
2.  Paste in the following (replace `YOUR_DOMAIN_OR_IP`):
    ```nginx
    server {
        listen 80;
        server_name YOUR_DOMAIN_OR_IP;
        root /var/www/naija-edu-pact/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        index index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```
3.  **Enable the site:**
    ```bash
    sudo ln -s /etc/nginx/sites-available/naija-edu-pact /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl restart nginx
    ```

### Step 8: Set Up Scheduler & Queue Worker

1.  **Install Supervisor:**
    ```bash
    sudo apt install supervisor
    ```
2.  **Create a config file** for the queue worker: `sudo nano /etc/supervisor/conf.d/naija-pact-worker.conf`
    ```ini
    [program:naija-pact-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /var/www/naija-edu-pact/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    user=ubuntu
    numprocs=2
    redirect_stderr=true
    stdout_logfile=/var/www/naija-edu-pact/storage/logs/worker.log
    ```
3.  **Start the worker:**
    ```bash
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start naija-pact-worker:*
    ```
4.  **Set up the Scheduler (Cron):**
    ```bash
    crontab -e
    ```
    Add this line to the end of the file:
    ```cron
    * * * * * cd /var/www/naija-edu-pact && php artisan schedule:run >> /dev/null 2>&1
    ```

Your application is now live and fully operational on AWS.