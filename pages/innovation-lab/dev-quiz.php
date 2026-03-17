<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Developer Skills Quiz — Innovation Lab — ' . APP_NAME;
$page_description = 'Test your knowledge across PHP, SQL, system design, APIs, and security with scored adaptive quizzes.';


$quiz_categories = [
  ['php',     '#F5A800', 'fa-php',         'PHP & Laravel',    'Variables, OOP, security, Laravel, PDO'],
  ['sql',     '#3B82F6', 'fa-database',    'SQL & Databases',  'Queries, joins, indexes, optimisation'],
  ['js',      '#22C55E', 'fa-js',          'JavaScript',       'ES6+, async/await, DOM, fetch API'],
  ['api',     '#A855F7', 'fa-plug',        'REST APIs',        'HTTP methods, auth, design principles'],
  ['security','#EF4444', 'fa-shield-halved','Security',        'OWASP Top 10, SQL injection, XSS, CSRF'],
  ['design',  '#F97316', 'fa-diagram-project','System Design', 'Scalability, architecture, databases'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Developer Quiz</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-graduation-cap"></i> Skills Quiz</span>
      <h1>Developer Skills Quiz</h1>
      <p>Test your technical knowledge — PHP, SQL, JavaScript, APIs, Security, and System Design. Get scored and see explanations.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container" style="max-width:800px;">

    <!-- Category Selection -->
    <div id="quiz-categories">
      <h3 style="margin-bottom:1.5rem;text-align:center;">Choose a Category</h3>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
        <?php foreach ($quiz_categories as $cat): ?>
        <button class="kw-card quiz-cat-btn" data-cat="<?= $cat[0] ?>" data-color="<?= $cat[1] ?>"
                style="padding:1.5rem;text-align:center;cursor:pointer;border-top:3px solid <?= $cat[1] ?>;transition:transform 0.2s;"
                onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
          <i class="fa-brands <?= $cat[2] ?>" style="font-size:2rem;color:<?= $cat[1] ?>;margin-bottom:0.75rem;display:block;"></i>
          <div style="font-weight:700;font-size:0.875rem;margin-bottom:0.2rem;"><?= $cat[3] ?></div>
          <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $cat[4] ?></div>
          <div style="margin-top:0.75rem;">
            <span style="background:<?= $cat[1] ?>15;color:<?= $cat[1] ?>;border:1px solid <?= $cat[1] ?>30;border-radius:999px;padding:0.2rem 0.65rem;font-size:0.7rem;font-weight:700;">10 Questions</span>
          </div>
        </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Quiz Panel -->
    <div id="quiz-panel" style="display:none;">
      <!-- Progress -->
      <div style="margin-bottom:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
          <span id="quiz-cat-label" style="font-size:0.8rem;font-weight:700;color:var(--kw-text-muted);"></span>
          <span style="font-size:0.8rem;color:var(--kw-text-muted);">Question <span id="quiz-q-num">1</span>/10 · Score: <span id="quiz-score" style="color:var(--kw-primary);font-weight:700;">0</span></span>
        </div>
        <div style="height:5px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
          <div id="quiz-progress" style="height:100%;background:var(--kw-primary);border-radius:999px;transition:width 0.4s;width:0%;"></div>
        </div>
      </div>

      <!-- Question -->
      <div id="quiz-question-card" class="kw-card" style="padding:2rem;">
        <div id="quiz-q-text" style="font-size:1rem;font-weight:600;margin-bottom:1.5rem;line-height:1.5;"></div>
        <div id="quiz-q-code" style="display:none;background:var(--kw-bg-hero);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;margin-bottom:1rem;font-family:monospace;font-size:0.82rem;color:#e5e7eb;white-space:pre;overflow-x:auto;"></div>
        <div id="quiz-options" style="display:flex;flex-direction:column;gap:0.6rem;"></div>
        <div id="quiz-explanation" style="display:none;margin-top:1.25rem;padding:1rem;border-radius:var(--kw-radius-md);font-size:0.85rem;line-height:1.65;"></div>
        <div id="quiz-next-wrap" style="display:none;margin-top:1.25rem;">
          <button id="quiz-next-btn" class="kw-btn kw-btn-primary">Next Question <i class="fa-solid fa-arrow-right"></i></button>
        </div>
      </div>
    </div>

    <!-- Result Panel -->
    <div id="quiz-result" style="display:none;" class="kw-card" style="padding:2.5rem;text-align:center;"></div>

  </div>
</section>

<script>
// Quiz question bank
const questions = {
  php: [
    { q: 'What does PDO stand for in PHP?', opts: ['PHP Data Object','PHP Database Object','PHP Data Operator','PHP Driver Object'], ans: 0, exp: 'PDO stands for PHP Data Object. It provides a consistent interface for accessing different database systems.' },
    { q: 'Which is the correct way to prevent SQL injection in PHP?', code: '$id = $_GET["id"];\n$q  = "SELECT * FROM users WHERE id = $id";', opts: ['Use htmlspecialchars()','Use PDO prepared statements','Use addslashes()','Validate as integer only'], ans: 1, exp: 'PDO prepared statements separate SQL code from data, making SQL injection impossible. Other options are insufficient defenses.' },
    { q: 'What is the output of password_hash("test", PASSWORD_BCRYPT)?', opts: ['Same hash every time','A different hash every time','An MD5 hash','An encrypted string'], ans: 1, exp: 'bcrypt generates a new random salt each time, so the same password produces a different hash. Use password_verify() to check.' },
    { q: 'In PHP, what is the difference between == and ===?', opts: ['No difference','=== also checks type','== is faster','=== works only for strings'], ans: 1, exp: '== compares values with type coercion (0 == "0" is true). === compares value AND type (0 === "0" is false). Use === for security-critical comparisons.' },
    { q: 'Which HTTP header helps prevent clickjacking attacks?', opts: ['X-XSS-Protection','X-Frame-Options','Content-Security-Policy','Strict-Transport-Security'], ans: 1, exp: 'X-Frame-Options: DENY or SAMEORIGIN prevents your page from being embedded in iframes on other sites, blocking clickjacking attacks.' },
    { q: 'What does session_regenerate_id() protect against?', opts: ['SQL injection','Session fixation attacks','XSS attacks','CSRF attacks'], ans: 1, exp: 'session_regenerate_id(true) creates a new session ID after login, preventing session fixation where an attacker pre-sets a known session ID.' },
    { q: 'In Laravel, what is the purpose of middleware?', opts: ['To define routes','To filter HTTP requests','To interact with the database','To render views'], ans: 1, exp: 'Middleware provides a mechanism for filtering HTTP requests entering your application — authentication, CORS, rate limiting, and logging are common uses.' },
    { q: 'What is the correct way to output user-provided data safely in PHP/HTML?', opts: ['echo $data','echo htmlspecialchars($data, ENT_QUOTES, "UTF-8")','echo strip_tags($data)','echo addslashes($data)'], ans: 1, exp: 'htmlspecialchars() with ENT_QUOTES converts special characters to HTML entities, preventing XSS attacks in HTML output contexts.' },
    { q: 'What does the return type of a function declared as : ?string mean?', opts: ['Returns only null','Returns a string or null','Returns a nullable string reference','Is a PHP error'], ans: 1, exp: 'The ?string return type (nullable type) means the function can return either a string or null. Introduced in PHP 7.1.' },
    { q: 'What is a CSRF token used for?', opts: ['Encrypting passwords','Verifying form submissions originate from your site','Preventing SQL injection','Encoding user data'], ans: 1, exp: 'CSRF tokens are unique, secret values embedded in forms. When submitted, the server verifies the token matches the session, confirming the request originated from your site.' },
  ],
  sql: [
    { q: 'What is the difference between INNER JOIN and LEFT JOIN?', opts: ['No difference','INNER returns only matching rows; LEFT returns all left rows','LEFT JOIN is faster','INNER JOIN works with more tables'], ans: 1, exp: 'INNER JOIN returns only rows with matching values in both tables. LEFT JOIN returns all rows from the left table, with NULLs for non-matching right rows.' },
    { q: 'Which SQL clause is used to filter groups?', opts: ['WHERE','FILTER','HAVING','GROUP BY'], ans: 2, exp: 'HAVING filters groups after GROUP BY aggregation. WHERE filters rows before grouping. You cannot use WHERE on aggregate functions like COUNT() or SUM().' },
    { q: 'What does adding an INDEX to a column do?', opts: ['Sorts the column','Speeds up SELECT queries on that column','Prevents duplicate values','Encrypts the column'], ans: 1, exp: 'An index creates a data structure that allows the database to find rows much faster without scanning the entire table. Trade-off: slightly slower writes.' },
    { q: 'What is a foreign key constraint?', opts: ['A primary key in another table','Ensures a column value exists in a related table','Prevents NULL values','Speeds up joins'], ans: 1, exp: 'A foreign key enforces referential integrity — the value must exist in the referenced table. Prevents orphaned records when rows are deleted.' },
    { q: 'What does SELECT DISTINCT do?', opts: ['Selects primary keys only','Returns unique rows (removes duplicates)','Selects encrypted values','Returns only indexed columns'], ans: 1, exp: 'DISTINCT removes duplicate rows from query results. Useful but can be slow on large datasets — consider if there is a data modelling issue causing duplicates.' },
    { q: 'What is the N+1 query problem?', opts: ['Running N queries that return +1 row','Loading N related records with 1 query per record instead of a JOIN','Having N+1 indexes','Running queries in a loop +1 time'], ans: 1, exp: 'N+1 occurs when you load N records then make 1 additional query per record for related data. Fix with JOINs or eager loading. Common cause of performance issues in ORMs.' },
    { q: 'What does EXPLAIN do in MySQL?', opts: ['Shows table schema','Shows query execution plan','Returns query results with metadata','Validates SQL syntax'], ans: 1, exp: 'EXPLAIN shows how MySQL executes a query — which indexes are used, row estimates, and join type. Essential for query optimisation.' },
    { q: 'What is the purpose of a database transaction?', opts: ['To speed up queries','To ensure a group of operations all succeed or all fail','To create indexes','To backup data'], ans: 1, exp: 'Transactions ensure atomicity (ACID). If any step fails, all changes are rolled back. Critical for financial operations, inventory deductions, and any multi-step data changes.' },
    { q: 'What does NULL mean in SQL?', opts: ['Zero value','Empty string','Unknown or missing value','False'], ans: 2, exp: 'NULL represents an unknown or missing value — not zero or empty string. NULL comparisons need IS NULL / IS NOT NULL, not = NULL.' },
    { q: 'Which SQL statement removes all rows from a table without logging individual deletions?', opts: ['DELETE FROM table','DROP TABLE table','TRUNCATE TABLE table','REMOVE FROM table'], ans: 2, exp: 'TRUNCATE removes all rows quickly without logging each deletion, and resets auto-increment counters. It cannot be rolled back in most databases.' },
  ],
  api: [
    { q: 'Which HTTP method is idempotent and used to fully replace a resource?', opts: ['POST','PATCH','PUT','DELETE'], ans: 2, exp: 'PUT replaces the entire resource. It is idempotent — calling it multiple times produces the same result. PATCH partially updates. POST creates. DELETE removes.' },
    { q: 'What HTTP status code should a successful POST (create) return?', opts: ['200 OK','201 Created','204 No Content','202 Accepted'], ans: 1, exp: '201 Created is the correct response for a successful resource creation. Return the created resource in the body and its URL in the Location header.' },
    { q: 'What does Bearer token authentication mean?', opts: ['Authentication via cookies','Sending a token in the Authorization header','Basic username:password encoding','Certificate-based auth'], ans: 1, exp: 'Bearer token auth sends a token in the HTTP header: Authorization: Bearer <token>. The server validates the token (JWT or opaque) to authenticate the request.' },
    { q: 'What is rate limiting in an API?', opts: ['Limiting response size','Restricting the number of requests a client can make in a time window','Limiting API to certain IPs','Reducing API endpoint count'], ans: 1, exp: 'Rate limiting prevents abuse by capping requests per client per time period (e.g. 100/hour). Returns 429 Too Many Requests when exceeded.' },
    { q: 'What does CORS stand for and what does it control?', opts: ['Client Origin Resource Sharing — client caching','Cross-Origin Resource Sharing — browser security policy for cross-domain requests','Content Origin Routing System','Cross-Origin Response Security'], ans: 1, exp: 'CORS (Cross-Origin Resource Sharing) controls which domains can make requests to your API from a browser. Configure via Access-Control-Allow-Origin headers.' },
    { q: 'What is the purpose of an API versioning strategy like /v1/ in URLs?', opts: ['Security obscurity','Allows breaking changes without disrupting existing clients','Required by HTTP spec','Improves performance'], ans: 1, exp: 'API versioning allows you to evolve the API (add/remove/change endpoints) while keeping the old version working for existing integrations.' },
    { q: 'What HTTP status code means the requested resource was not found?', opts: ['400','401','403','404'], ans: 3, exp: '404 Not Found means the resource does not exist. 400 = bad request. 401 = unauthenticated. 403 = forbidden (authenticated but not authorised).' },
    { q: 'What is a webhook?', opts: ['A tool for testing APIs','An HTTP callback sent by a server when an event occurs','A type of API authentication','A query language for APIs'], ans: 1, exp: 'A webhook sends an HTTP POST to your specified URL when an event occurs on the provider (e.g. payment received, new subscriber). The opposite of polling.' },
    { q: 'What does JSON Web Token (JWT) contain?', opts: ['Encrypted user data','Base64-encoded header, payload, and signature','API keys','Session IDs'], ans: 1, exp: 'A JWT has three Base64URL-encoded parts: header (algorithm), payload (claims/data), and signature (verified server-side). Payload is readable — never put sensitive data there.' },
    { q: 'What is idempotency in REST APIs?', opts: ['Returning the same response format','An operation that produces the same result no matter how many times it is called','Making APIs stateless','Caching API responses'], ans: 1, exp: 'An idempotent operation can be called multiple times with the same effect as calling once. GET, PUT, DELETE are idempotent. POST is not. Important for safe retry logic.' },
  ],
  security: [
    { q: 'What is SQL injection?', opts: ['Inserting SQL code into a database','Injecting malicious SQL through user input to manipulate database queries','A way to speed up SQL queries','A method of SQL encryption'], ans: 1, exp: 'SQL injection occurs when user input is concatenated into SQL queries without sanitisation, allowing attackers to manipulate the query logic, bypass auth, or exfiltrate data.' },
    { q: 'What is Cross-Site Scripting (XSS)?', opts: ['Stealing CSS stylesheets','Injecting malicious scripts into web pages viewed by other users','Cross-origin resource sharing','Scripting across multiple servers'], ans: 1, exp: 'XSS attacks inject malicious JavaScript into pages. When other users view the page, the script executes in their browser, potentially stealing cookies or credentials.' },
    { q: 'What does HTTPS provide that HTTP does not?', opts: ['Faster connections','Encryption in transit and server identity verification','Better SEO only','Automatic redirects'], ans: 1, exp: 'HTTPS encrypts data in transit (preventing eavesdropping and tampering) and verifies server identity via SSL/TLS certificates. HTTP sends data in plaintext.' },
    { q: 'What is the principle of least privilege?', opts: ['Granting admin access by default','Giving users/processes only the minimum permissions required for their function','Using the simplest possible passwords','Limiting database tables'], ans: 1, exp: 'Least privilege limits access to only what is needed. If a user account is compromised, limited permissions reduce the blast radius.' },
    { q: 'What is a CSRF attack?', opts: ['Cross-Site Scripting attack','Tricking a user\'s browser into making an unwanted request to a site they\'re authenticated on','Stealing HTTPS certificates','Brute-forcing passwords'], ans: 1, exp: 'CSRF tricks an authenticated user\'s browser into sending a malicious request (e.g. transfer funds). Mitigated with CSRF tokens that validate the request originated from your site.' },
    { q: 'What hashing algorithm should be used for passwords?', opts: ['MD5','SHA-256','bcrypt or Argon2','SHA-1'], ans: 2, exp: 'MD5 and SHA variants are too fast — designed for speed, not password security. bcrypt/Argon2 are slow by design (configurable cost factor), making brute force attacks expensive.' },
    { q: 'What is directory traversal attack?', opts: ['Navigating website menus','Using "../" sequences in file paths to access files outside the intended directory','Traversing database indexes','Accessing admin directories directly'], ans: 1, exp: 'Directory traversal uses ../ sequences to navigate outside the intended directory. E.g. /file?name=../../etc/passwd. Mitigate by validating and sanitising file path inputs.' },
    { q: 'What does a Content Security Policy (CSP) header do?', opts: ['Blocks all JavaScript','Specifies allowed content sources to prevent XSS and data injection','Encrypts page content','Controls CORS'], ans: 1, exp: 'CSP tells the browser which sources are allowed for scripts, styles, images, etc. A strict CSP prevents malicious injected scripts from executing even if XSS is present.' },
    { q: 'What is the purpose of input validation?', opts: ['Improving UX','Ensuring data conforms to expected format before processing to prevent injection attacks','Checking internet connectivity','Validating SSL certificates'], ans: 1, exp: 'Input validation ensures data is the expected type, format, and range before processing. Never trust client-side validation alone — always validate server-side.' },
    { q: 'What is a man-in-the-middle attack?', opts: ['An admin intercepting user logs','An attacker secretly intercepting and possibly altering communication between two parties','A proxy server','Social engineering attack'], ans: 1, exp: 'MITM attacks intercept communication between client and server, potentially reading or altering data. HTTPS with certificate pinning and HSTS mitigate this.' },
  ],
  js: [
    { q: 'What is the difference between let, const, and var?', opts: ['No difference','var is function-scoped; let and const are block-scoped; const cannot be reassigned','const is faster','let and var are the same'], ans: 1, exp: 'var is function-scoped and hoisted. let and const are block-scoped. const cannot be reassigned (the value itself can still be mutated for objects/arrays).' },
    { q: 'What does async/await do in JavaScript?', opts: ['Speeds up synchronous code','Provides syntactic sugar over Promises for handling asynchronous code','Creates multi-threaded execution','Delays code execution'], ans: 1, exp: 'async/await makes asynchronous Promise-based code look synchronous. await pauses execution inside an async function until the Promise resolves.' },
    { q: 'What is the purpose of the fetch() API?', opts: ['Fetching DOM elements','Making HTTP requests from the browser','Loading external CSS','Parsing JSON'], ans: 1, exp: 'fetch() makes HTTP requests from the browser and returns Promises. Modern replacement for XMLHttpRequest. Chain .then() or use await for the response.' },
    { q: 'What does JSON.parse() do?', opts: ['Converts JS object to JSON string','Converts a JSON string to a JavaScript object','Validates JSON','Encrypts data'], ans: 1, exp: 'JSON.parse() converts a JSON-formatted string into a JavaScript object/array. Use JSON.stringify() for the reverse. Wrap in try/catch for malformed input.' },
    { q: 'What is event delegation?', opts: ['Passing events between functions','Attaching one event listener to a parent element to handle events from child elements','Scheduling events','Blocking event propagation'], ans: 1, exp: 'Event delegation uses event bubbling — attach one listener to a parent and check event.target to handle child elements. More efficient than many individual listeners.' },
    { q: 'What does the spread operator (...) do?', opts: ['Creates a variable number of parameters only','Expands an iterable into individual elements','Spreads across multiple files','Creates deep copies always'], ans: 1, exp: 'The spread operator expands iterables. [...arr1, ...arr2] merges arrays. {...obj1, ...obj2} merges objects. Also used for function rest parameters.' },
    { q: 'What is a closure in JavaScript?', opts: ['A way to close the browser','A function that has access to its outer scope even after the outer function has returned','An IIFE pattern','A module pattern'], ans: 1, exp: 'A closure is formed when an inner function retains access to its outer scope (variables and parameters) even after the outer function has finished executing.' },
    { q: 'What is the difference between == and === in JavaScript?', opts: ['No difference','=== checks value and type; == performs type coercion first','=== is slower','== only works with numbers'], ans: 1, exp: '== coerces types (0 == "0" is true, "" == false is true). === checks value AND type with no coercion. Always prefer === to avoid unexpected behaviour.' },
    { q: 'What does localStorage do?', opts: ['Stores data on the server','Stores key-value data in the browser that persists after the window is closed','Stores session data only','Creates cookies'], ans: 1, exp: 'localStorage stores key-value pairs in the browser with no expiry (unlike sessionStorage). Data persists across sessions. Accessible only via JavaScript — never store sensitive data here.' },
    { q: 'What is a Promise in JavaScript?', opts: ['A guaranteed function execution','An object representing the eventual completion or failure of an async operation','A callback function','A synchronous operation wrapper'], ans: 1, exp: 'A Promise represents a value not yet known. It can be pending, fulfilled (resolved), or rejected. Chain .then()/.catch() or use await to work with the result.' },
  ],
  design: [
    { q: 'What is horizontal scaling (scaling out)?', opts: ['Adding more RAM to one server','Adding more servers to distribute load','Increasing CPU speed','Upgrading to SSD storage'], ans: 1, exp: 'Horizontal scaling adds more server instances to handle load. More resilient than vertical scaling (single larger server). Requires stateless application design and load balancing.' },
    { q: 'What problem does database connection pooling solve?', opts: ['SQL injection','The overhead of creating new database connections for each request','Slow SQL queries','Database replication lag'], ans: 1, exp: 'Creating a DB connection is expensive (10-100ms). A connection pool maintains pre-opened connections and reuses them, dramatically improving performance under load.' },
    { q: 'What is the purpose of a message queue (like Redis Queue)?', opts: ['Storing messages between users','Decoupling services and handling tasks asynchronously to prevent blocking','Caching database results','Routing HTTP requests'], ans: 1, exp: 'Message queues decouple producers and consumers. Long tasks (email sending, report generation) go into a queue and are processed by workers asynchronously, improving response times.' },
    { q: 'What is the CAP theorem?', opts: ['A distributed systems design pattern','In a distributed system, you can only guarantee 2 of 3: Consistency, Availability, Partition Tolerance','A caching strategy','A database normalisation rule'], ans: 1, exp: 'CAP theorem states distributed systems can only guarantee two of: Consistency (all nodes see same data), Availability (every request gets a response), Partition Tolerance (works despite network failures).' },
    { q: 'What is database normalisation?', opts: ['Compressing database files','Organising database tables to reduce redundancy and improve data integrity','Making all column names lowercase','Indexing all columns'], ans: 1, exp: 'Normalisation organises data into tables to eliminate redundancy (1NF, 2NF, 3NF). Reduces update anomalies. But highly normalised schemas can require complex joins — sometimes denormalise for performance.' },
    { q: 'What is a CDN (Content Delivery Network) used for?', opts: ['A private corporate network','Delivering content from servers geographically close to users to reduce latency','Encrypting all web traffic','Managing DNS records'], ans: 1, exp: 'CDNs cache static assets (images, CSS, JS) at edge nodes worldwide. Requests are served from the nearest node, reducing latency and origin server load.' },
    { q: 'What is the purpose of caching in a web application?', opts: ['Storing user sessions','Storing computed results to avoid repeating expensive operations','Backing up the database','Logging user activity'], ans: 1, exp: 'Caching stores expensive computation results (DB queries, API responses) in fast storage (Redis, Memcached). Subsequent requests use the cache instead of recomputing.' },
    { q: 'What is a microservices architecture?', opts: ['Very small JavaScript files','An architecture where an application is built as a suite of small, independently deployable services','A database pattern','A UI component pattern'], ans: 1, exp: 'Microservices split applications into small, independent services (each responsible for one business domain) that communicate via APIs. Pros: scale independently, fault isolation. Cons: complexity, network overhead.' },
    { q: 'What does "stateless" mean for API design?', opts: ['The API has no state machine','Each request must contain all information needed — the server stores no session state between requests','The API returns no data','The API has no authentication'], ans: 1, exp: 'Stateless APIs treat each request independently with no stored session context. All authentication, context, and data must be in each request. Required for horizontal scaling.' },
    { q: 'What is the primary purpose of an API Gateway?', opts: ['To store API documentation','A single entry point for all clients that handles routing, auth, rate limiting, and load balancing','To test API endpoints','To manage database connections'], ans: 1, exp: 'An API Gateway is a reverse proxy that provides a unified entry point. Handles cross-cutting concerns: authentication, rate limiting, logging, SSL termination, request routing.' },
  ],
};

let currentCat   = null;
let currentQ     = 0;
let score        = 0;
let userAnswers  = [];
let catColor     = '#F5A800';

document.querySelectorAll('.quiz-cat-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    currentCat  = btn.dataset.cat;
    catColor    = btn.dataset.color;
    score       = 0;
    currentQ    = 0;
    userAnswers = [];
    document.getElementById('quiz-categories').style.display = 'none';
    document.getElementById('quiz-panel').style.display = 'block';
    document.getElementById('quiz-cat-label').textContent = btn.querySelector('div:nth-child(2)').textContent + ' Quiz';
    document.getElementById('quiz-cat-label').style.color = catColor;
    showQuestion(0);
  });
});

function showQuestion(qi) {
  const q = questions[currentCat][qi];
  document.getElementById('quiz-q-num').textContent = qi + 1;
  document.getElementById('quiz-score').textContent = score;
  document.getElementById('quiz-progress').style.width = ((qi / 10) * 100) + '%';
  document.getElementById('quiz-q-text').textContent = q.q;

  const codeEl = document.getElementById('quiz-q-code');
  if (q.code) { codeEl.style.display = 'block'; codeEl.textContent = q.code; }
  else { codeEl.style.display = 'none'; }

  const opts = document.getElementById('quiz-options');
  opts.innerHTML = q.opts.map((opt, i) => `
    <button class="kw-btn kw-btn-ghost quiz-opt" data-i="${i}" style="justify-content:flex-start;text-align:left;padding:0.75rem 1rem;font-size:0.875rem;transition:all 0.2s;">
      <span style="width:26px;height:26px;border-radius:50%;background:var(--kw-bg-alt);border:1.5px solid var(--kw-border);display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;margin-right:0.75rem;flex-shrink:0;">${String.fromCharCode(65+i)}</span>
      ${opt}
    </button>
  `).join('');

  document.getElementById('quiz-explanation').style.display = 'none';
  document.getElementById('quiz-next-wrap').style.display = 'none';

  document.querySelectorAll('.quiz-opt').forEach(btn => {
    btn.addEventListener('click', () => handleAnswer(parseInt(btn.dataset.i)));
  });
}

function handleAnswer(ai) {
  const q   = questions[currentCat][currentQ];
  const correct = ai === q.ans;
  if (correct) score++;
  userAnswers.push({ qi: currentQ, chosen: ai, correct });

  // Highlight answers
  document.querySelectorAll('.quiz-opt').forEach(btn => {
    btn.disabled = true;
    const i = parseInt(btn.dataset.i);
    if (i === q.ans) { btn.style.background='#22c55e20'; btn.style.borderColor='#22C55E'; btn.style.color='#22C55E'; }
    else if (i === ai && !correct) { btn.style.background='#ef444420'; btn.style.borderColor='#EF4444'; btn.style.color='#EF4444'; }
  });

  // Explanation
  const exp = document.getElementById('quiz-explanation');
  exp.style.display = 'block';
  exp.style.background = correct ? '#22c55e10' : '#ef444410';
  exp.style.border = `1px solid ${correct ? '#22c55e30' : '#ef444430'}`;
  exp.innerHTML = `<strong>${correct ? '✅ Correct!' : '❌ Incorrect.'}</strong> ${q.exp}`;

  document.getElementById('quiz-q-num').textContent = currentQ + 1;
  document.getElementById('quiz-score').textContent = score;
  document.getElementById('quiz-next-wrap').style.display = 'block';

  const nextBtn = document.getElementById('quiz-next-btn');
  nextBtn.onclick = () => {
    if (currentQ < 9) {
      currentQ++;
      showQuestion(currentQ);
    } else {
      showResult();
    }
  };
  if (currentQ === 9) {
    nextBtn.innerHTML = 'See Results <i class="fa-solid fa-trophy"></i>';
  }
}

function showResult() {
  document.getElementById('quiz-panel').style.display = 'none';
  const r = document.getElementById('quiz-result');
  r.style.display = 'block';

  const pct = score * 10;
  const grade = score >= 9 ? ['🏆','Expert','#F5A800'] : score >= 7 ? ['⭐','Advanced','#22C55E'] : score >= 5 ? ['👍','Intermediate','#3B82F6'] : ['📚','Beginner','#F97316'];

  r.innerHTML = `
    <div style="padding:2.5rem;text-align:center;">
      <div style="font-size:3rem;margin-bottom:0.5rem;">${grade[0]}</div>
      <div style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:${grade[2]};margin-bottom:0.5rem;">${grade[1]}</div>
      <h2 style="margin-bottom:0.5rem;">${score}/10 Correct</h2>
      <div style="height:8px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;max-width:300px;margin:1rem auto 1.5rem;">
        <div style="height:100%;background:${grade[2]};border-radius:999px;width:${pct}%;transition:width 1s;"></div>
      </div>
      <p style="color:var(--kw-text-muted);margin-bottom:2rem;">
        ${pct}% accuracy · ${score >= 8 ? 'Excellent technical knowledge!' : score >= 6 ? 'Good foundation — keep building.' : 'Room to grow — review the explanations.'}
      </p>
      <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
        <button onclick="retryQuiz()" class="kw-btn kw-btn-ghost"><i class="fa-solid fa-rotate-left"></i> Try Again</button>
        <button onclick="document.getElementById('quiz-categories').style.display='block';document.getElementById('quiz-result').style.display='none';" class="kw-btn kw-btn-ghost">
          <i class="fa-solid fa-list"></i> Other Categories
        </button>
        <a href="<?= url('innovation-lab') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-flask"></i> More Lab Tools</a>
      </div>
    </div>
  `;
}

function retryQuiz() {
  score=0; currentQ=0; userAnswers=[];
  document.getElementById('quiz-result').style.display='none';
  document.getElementById('quiz-panel').style.display='block';
  showQuestion(0);
}
</script>
<style>
@media(max-width:768px){
  #quiz-categories > div{ grid-template-columns:repeat(2,1fr)!important; }
}
@media(max-width:480px){
  #quiz-categories > div{ grid-template-columns:1fr!important; }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>