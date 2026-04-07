FROM mcr.microsoft.com/devcontainers/base:ubuntu-22.04

# ── System packages ────────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    software-properties-common curl git unzip zip sqlite3 \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update && apt-get install -y \
    php8.2 php8.2-cli php8.2-common php8.2-curl \
    php8.2-mbstring php8.2-xml php8.2-zip \
    php8.2-sqlite3 php8.2-pdo php8.2-intl \
    && rm -rf /var/lib/apt/lists/*

# ── Composer 2 ─────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── Node.js LTS (via NodeSource) ───────────────────────────────────────────────
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Non-root user (vscode) already exists in the base image
USER vscode
