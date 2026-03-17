-- ============================================================
-- KRESTWORKS SOLUTIONS — DATABASE SCHEMA
-- Version: 1.0.0
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+03:00';

-- ============================================================
-- USERS & AUTH
-- ============================================================

CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)    NOT NULL,
    email           VARCHAR(150)    NOT NULL UNIQUE,
    password        VARCHAR(255)    NOT NULL,
    company         VARCHAR(150)    DEFAULT NULL,
    phone           VARCHAR(30)     DEFAULT NULL,
    avatar          VARCHAR(255)    DEFAULT NULL,
    role            ENUM('client','admin','moderator') DEFAULT 'client',
    is_active       TINYINT(1)      DEFAULT 1,
    email_verified  TINYINT(1)      DEFAULT 0,
    verify_token    VARCHAR(100)    DEFAULT NULL,
    reset_token     VARCHAR(100)    DEFAULT NULL,
    reset_expires   DATETIME        DEFAULT NULL,
    last_login      DATETIME        DEFAULT NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- PRODUCTS / SYSTEMS
-- ============================================================

CREATE TABLE IF NOT EXISTS products (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug                VARCHAR(100)    NOT NULL UNIQUE,
    name                VARCHAR(150)    NOT NULL,
    tagline             VARCHAR(255)    DEFAULT NULL,
    description         TEXT            DEFAULT NULL,
    long_description    LONGTEXT        DEFAULT NULL,
    category            VARCHAR(100)    DEFAULT NULL,
    features            JSON            DEFAULT NULL,
    tech_stack          JSON            DEFAULT NULL,
    deployment_options  JSON            DEFAULT NULL,
    screenshots         JSON            DEFAULT NULL,
    pricing_starter     DECIMAL(10,2)   DEFAULT NULL,
    pricing_pro         DECIMAL(10,2)   DEFAULT NULL,
    pricing_enterprise  VARCHAR(50)     DEFAULT 'Contact Us',
    icon                VARCHAR(100)    DEFAULT 'fa-cube',
    color_accent        VARCHAR(20)     DEFAULT '#F5A800',
    is_featured         TINYINT(1)      DEFAULT 0,
    is_active           TINYINT(1)      DEFAULT 1,
    sort_order          INT             DEFAULT 0,
    created_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SERVICES
-- ============================================================

CREATE TABLE IF NOT EXISTS services (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(100)    NOT NULL UNIQUE,
    name        VARCHAR(150)    NOT NULL,
    tagline     VARCHAR(255)    DEFAULT NULL,
    description TEXT            DEFAULT NULL,
    features    JSON            DEFAULT NULL,
    icon        VARCHAR(100)    DEFAULT 'fa-cog',
    is_active   TINYINT(1)      DEFAULT 1,
    sort_order  INT             DEFAULT 0,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- INQUIRIES (Contact / Demo / Consultation)
-- ============================================================

CREATE TABLE IF NOT EXISTS inquiries (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type            ENUM('contact','demo','consultation','newsletter') NOT NULL,
    name            VARCHAR(100)    DEFAULT NULL,
    email           VARCHAR(150)    DEFAULT NULL,
    phone           VARCHAR(30)     DEFAULT NULL,
    company         VARCHAR(150)    DEFAULT NULL,
    subject         VARCHAR(255)    DEFAULT NULL,
    message         TEXT            DEFAULT NULL,
    product_interest VARCHAR(100)   DEFAULT NULL,
    service_interest VARCHAR(100)   DEFAULT NULL,
    preferred_date  DATE            DEFAULT NULL,
    preferred_time  VARCHAR(50)     DEFAULT NULL,
    consultation_type VARCHAR(100)  DEFAULT NULL,
    status          ENUM('new','read','in_progress','resolved','archived') DEFAULT 'new',
    admin_notes     TEXT            DEFAULT NULL,
    ip_address      VARCHAR(45)     DEFAULT NULL,
    user_agent      VARCHAR(300)    DEFAULT NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- BLOG POSTS
-- ============================================================

CREATE TABLE IF NOT EXISTS blog_posts (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(200)    NOT NULL UNIQUE,
    title           VARCHAR(255)    NOT NULL,
    excerpt         TEXT            DEFAULT NULL,
    content         LONGTEXT        DEFAULT NULL,
    cover_image     VARCHAR(255)    DEFAULT NULL,
    category        VARCHAR(100)    DEFAULT NULL,
    tags            JSON            DEFAULT NULL,
    author_id       INT UNSIGNED    DEFAULT NULL,
    views           INT UNSIGNED    DEFAULT 0,
    is_published    TINYINT(1)      DEFAULT 0,
    is_featured     TINYINT(1)      DEFAULT 0,
    published_at    DATETIME        DEFAULT NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_published (is_published),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- AI TOOLS
-- ============================================================

CREATE TABLE IF NOT EXISTS ai_tools (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(100)    NOT NULL UNIQUE,
    name            VARCHAR(150)    NOT NULL,
    description     TEXT            DEFAULT NULL,
    category        ENUM('free','premium') DEFAULT 'free',
    icon            VARCHAR(100)    DEFAULT 'fa-robot',
    tool_type       VARCHAR(100)    DEFAULT NULL,
    system_prompt   TEXT            DEFAULT NULL,
    placeholder_text VARCHAR(255)   DEFAULT NULL,
    is_active       TINYINT(1)      DEFAULT 1,
    sort_order      INT             DEFAULT 0,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ai_tool_usage (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tool_slug   VARCHAR(100)    NOT NULL,
    user_id     INT UNSIGNED    DEFAULT NULL,
    ip_address  VARCHAR(45)     DEFAULT NULL,
    tokens_used INT UNSIGNED    DEFAULT 0,
    used_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tool (tool_slug),
    INDEX idx_used_at (used_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- COMMUNITY
-- ============================================================

CREATE TABLE IF NOT EXISTS community_posts (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    title       VARCHAR(255)    NOT NULL,
    body        TEXT            NOT NULL,
    category    ENUM('discussion','question','insight','tutorial','news') DEFAULT 'discussion',
    tags        JSON            DEFAULT NULL,
    views       INT UNSIGNED    DEFAULT 0,
    likes       INT UNSIGNED    DEFAULT 0,
    is_pinned   TINYINT(1)      DEFAULT 0,
    is_active   TINYINT(1)      DEFAULT 1,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_category (category),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS community_replies (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id     INT UNSIGNED    NOT NULL,
    user_id     INT UNSIGNED    NOT NULL,
    body        TEXT            NOT NULL,
    likes       INT UNSIGNED    DEFAULT 0,
    is_accepted TINYINT(1)      DEFAULT 0,
    is_active   TINYINT(1)      DEFAULT 1,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES community_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS community_likes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    target_type ENUM('post','reply') NOT NULL,
    target_id   INT UNSIGNED    NOT NULL,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_like (user_id, target_type, target_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SUBSCRIPTIONS & LICENSING
-- ============================================================

CREATE TABLE IF NOT EXISTS subscriptions (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED    NOT NULL,
    product_id      INT UNSIGNED    NOT NULL,
    plan            ENUM('monthly','annual','enterprise','trial') DEFAULT 'monthly',
    status          ENUM('active','expired','cancelled','pending') DEFAULT 'pending',
    amount          DECIMAL(10,2)   DEFAULT 0.00,
    currency        VARCHAR(10)     DEFAULT 'KES',
    starts_at       DATE            DEFAULT NULL,
    expires_at      DATE            DEFAULT NULL,
    auto_renew      TINYINT(1)      DEFAULT 1,
    transaction_ref VARCHAR(100)    DEFAULT NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- ANALYTICS / SITE EVENTS
-- ============================================================

CREATE TABLE IF NOT EXISTS site_events (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type  VARCHAR(100)    NOT NULL,
    page        VARCHAR(255)    DEFAULT NULL,
    user_id     INT UNSIGNED    DEFAULT NULL,
    ip_address  VARCHAR(45)     DEFAULT NULL,
    user_agent  VARCHAR(300)    DEFAULT NULL,
    meta        JSON            DEFAULT NULL,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event (event_type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- RATE LIMITING
-- ============================================================

CREATE TABLE IF NOT EXISTS rate_limits (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address  VARCHAR(45)     NOT NULL,
    action      VARCHAR(100)    NOT NULL,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_action (ip_address, action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- NEWSLETTER
-- ============================================================

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(150)    NOT NULL UNIQUE,
    name        VARCHAR(100)    DEFAULT NULL,
    is_active   TINYINT(1)      DEFAULT 1,
    subscribed_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SUPPORT TICKETS (Portal)
-- ============================================================

CREATE TABLE IF NOT EXISTS support_tickets (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    subject     VARCHAR(255)    NOT NULL,
    message     TEXT            NOT NULL,
    priority    ENUM('low','medium','high','critical') DEFAULT 'medium',
    status      ENUM('open','in_progress','resolved','closed') DEFAULT 'open',
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ticket_replies (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id   INT UNSIGNED    NOT NULL,
    user_id     INT UNSIGNED    NOT NULL,
    message     TEXT            NOT NULL,
    is_admin    TINYINT(1)      DEFAULT 0,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- INNOVATION LAB — Tool Sessions
-- ============================================================

CREATE TABLE IF NOT EXISTS lab_sessions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tool_slug   VARCHAR(100)    NOT NULL,
    user_id     INT UNSIGNED    DEFAULT NULL,
    ip_address  VARCHAR(45)     DEFAULT NULL,
    session_data JSON           DEFAULT NULL,
    score       INT             DEFAULT NULL,
    completed   TINYINT(1)      DEFAULT 0,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tool (tool_slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SEED DATA — Default Admin User
-- Password: Admin@Krest2024 (change immediately)
-- ============================================================

INSERT IGNORE INTO users (name, email, password, role, is_active, email_verified)
VALUES (
    'Krestworks Admin',
    'admin@krestworks.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    1,
    1
);

-- ============================================================
-- SEED DATA — Products
-- ============================================================

INSERT IGNORE INTO products (slug, name, tagline, description, features, icon, is_featured, sort_order) VALUES
('hr-system', 'HR Management System', 'Streamline your entire human resource lifecycle', 'A comprehensive HR platform covering recruitment, payroll, performance, and compliance.', '["Employee Records","Payroll Processing","Leave Management","Performance Tracking","Recruitment","HR Analytics","Time & Attendance","Training Management","Compliance Reports"]', 'fa-users', 1, 1),
('procurement-system', 'Procurement Management System', 'End-to-end procurement and supplier management', 'Automate procurement workflows from purchase requisition to invoice reconciliation.', '["Supplier Management","Purchase Workflows","Invoice Processing","Approval Automation","Spend Analytics","Budget Tracking","Contract Management","Financial Integration"]', 'fa-shopping-cart', 1, 2),
('elearning-system', 'eLearning Management System', 'Deliver and manage learning at scale', 'A full-featured LMS for academic institutions and corporate training programs.', '["Course Management","Student Portal","Exams & Grading","Progress Analytics","Certification System","Live Classes","Content Library","Bulk Enrollment"]', 'fa-graduation-cap', 1, 3),
('real-estate-system', 'Real Estate Management System', 'Manage properties, tenants, and leases in one place', 'Complete real estate operations platform for landlords, agents, and property managers.', '["Property Management","Tenant Management","Lease Tracking","Rent Automation","Maintenance Tracking","Financial Reports","Document Storage","Tenant Portal"]', 'fa-building', 1, 4),
('supply-chain-system', 'Supply Chain Management System', 'Optimize your supply chain end-to-end', 'Full visibility and control across inventory, logistics, warehousing, and order fulfillment.', '["Inventory Tracking","Logistics Management","Warehouse Control","Order Processing","Supplier Integration","Demand Forecasting","Multi-location Support","Real-time Analytics"]', 'fa-truck', 0, 5),
('decision-support-system', 'Executive Decision Support', 'AI-powered insights for strategic decisions', 'Enterprise analytics and business intelligence platform built for C-suite decision makers.', '["Business Analytics","Data Visualization","AI Insights","KPI Dashboards","Scenario Modeling","Report Builder","Multi-source Integration","Alerts & Notifications"]', 'fa-chart-line', 0, 6),
('crm-system', 'CRM System', 'Turn leads into loyal customers', 'Customer relationship management built for sales teams, with pipeline tracking and AI scoring.', '["Lead Management","Sales Pipeline","Contact Management","Email Integration","Activity Tracking","Deal Forecasting","Customer Segmentation","Reports & Analytics"]', 'fa-handshake', 0, 7),
('hospital-system', 'Hospital Management System', 'Integrated healthcare operations platform', 'Manage patients, appointments, billing, pharmacy, and lab all in one connected system.', '["Patient Records (EMR)","Appointment Scheduling","Billing & Insurance","Pharmacy Management","Lab Integration","Doctor Portal","Nurse Station","Analytics Dashboard"]', 'fa-hospital', 0, 8),
('pos-system', 'Point of Sale System', 'Fast, reliable POS for retail and hospitality', 'Cloud-based POS with inventory sync, multi-branch support, and integrated payments.', '["Sales Processing","Inventory Sync","Multi-branch","Payment Integration","Receipt Printing","Daily Reports","Customer Loyalty","Offline Mode"]', 'fa-cash-register', 0, 9);

-- ============================================================
-- SEED DATA — AI Tools
-- ============================================================

INSERT IGNORE INTO ai_tools (slug, name, description, category, icon, sort_order) VALUES
('document-summarizer', 'Document Summarizer', 'Paste any document and get a clean, structured summary instantly.', 'free', 'fa-file-alt', 1),
('resume-analyzer', 'Resume Analyzer', 'Upload a resume and get AI feedback on strengths, gaps, and improvements.', 'free', 'fa-id-card', 2),
('code-assistant', 'Code Assistant', 'Get AI help with code review, debugging, and generation across languages.', 'free', 'fa-code', 3),
('meeting-notes', 'Meeting Notes Generator', 'Paste raw meeting notes and get formatted minutes with action items.', 'free', 'fa-clipboard-list', 4),
('business-strategy-ai', 'Business Strategy AI', 'AI-powered strategic analysis for your business goals and market position.', 'premium', 'fa-chess', 5),
('financial-analysis-ai', 'Financial Analysis AI', 'Analyze financial data and get AI-generated insights and forecasts.', 'premium', 'fa-chart-bar', 6),
('sales-forecasting', 'Sales Forecasting AI', 'Predict revenue trends using historical data and market signals.', 'premium', 'fa-trending-up', 7),
('data-insight-generator', 'Data Insight Generator', 'Upload datasets and let AI surface the most important patterns and insights.', 'premium', 'fa-database', 8);