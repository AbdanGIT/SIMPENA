<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     *
     * URL to your CodeIgniter root. Typically, this will be your base URL,
     * WITH a trailing slash:
     *
     * E.g., http://example.com/
     */
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Deteksi baseURL otomatis dari request (localhost, IP LAN, atau nama host).
     *
     * Dulu baseURL dipatok 'http://localhost/SIMPENA/public/' di .env, akibatnya
     * saat aplikasi dibuka dari HP / PC lain (mis. http://10.51.47.129/SIMPENA/public/)
     * semua link aset (CSS/JS), action form, dan redirect tetap menunjuk ke
     * "localhost" milik perangkat pengguna sehingga CSS tidak termuat dan login
     * terlempar ke aplikasi lain yang berjalan di localhost perangkat tersebut.
     *
     * Urutan prioritas:
     *   1. app.baseURL di .env (kalau diisi) -> dipakai apa adanya.
     *   2. Host dari request + folder skrip (mis. /SIMPENA/public/).
     *   3. Nilai default di atas (dipakai saat CLI / host tidak valid).
     */
    public function __construct()
    {
        parent::__construct();

        // Baca nilai .env tanpa bergantung pada helper env() (belum tentu
        // dimuat saat konfigurasi dibuat). DotEnv menulis ke $_ENV/$_SERVER.
        // getenv() sengaja tidak dipakai: pada FastCGI prosesnya bertahan antar
        // request, sehingga nilai lama bisa "membeku" walau .env sudah diubah.
        $envBaseURL = $_ENV['app.baseURL'] ?? $_SERVER['app.baseURL'] ?? '';

        if (is_string($envBaseURL) && trim($envBaseURL) !== '') {
            $this->baseURL = rtrim(trim($envBaseURL), '/') . '/';

            return;
        }

        $this->baseURL = $this->deteksiBaseURL();
    }

    private function deteksiBaseURL(): string
    {
        // Request dari CLI (spark, cron) tidak punya host.
        if (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') {
            return $this->baseURL;
        }

        $host = strtolower(trim((string) ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '')));

        // Tolak host aneh (header injection). Dukungan IPv4/IPv6 + port.
        if ($host === '' || preg_match('/^(?:[a-z0-9.\-]+|\[[0-9a-f:]+\])(?::\d+)?$/', $host) !== 1) {
            return $this->baseURL;
        }

        // Kalau allowedHostnames diisi, hanya host itu yang boleh jadi baseURL.
        if ($this->allowedHostnames !== []) {
            $hostTanpaPort = explode(':', str_replace(['[', ']'], '', $host))[0];

            if (! in_array($hostTanpaPort, array_map('strtolower', $this->allowedHostnames), true)) {
                return $this->baseURL;
            }
        }

        $https = (! empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';

        // Folder tempat index.php dieksekusi, mis. "/SIMPENA/public". Nilainya
        // mengikuti huruf asli folder di disk (Apache menormalkan case).
        $dir = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'))), '/');

        // Path yang benar-benar diminta pengguna, tanpa query string.
        $requestPath = str_replace('\\', '/', (string) strtok((string) ($_SERVER['REQUEST_URI'] ?? '/'), '?'));

        if (preg_match('#/public$#i', $dir) === 1) {
            $tanpaPublic = substr($dir, 0, -7); // buang "/public"

            // Pengguna mengetik "/public" secara eksplisit?
            $lewatPublic = stripos($requestPath, $dir . '/') === 0
                || strcasecmp(rtrim($requestPath, '/'), $dir) === 0;

            $acuan = $lewatPublic ? $dir : $tanpaPublic;

            // Pakai huruf yang diketik pengguna (mis. "/simpena", bukan
            // "/SIMPENA"), supaya perbandingan path di CodeIgniter cocok dan
            // link yang dihasilkan konsisten dengan URL di address bar.
            $panjang = strlen($acuan);
            $dir     = strcasecmp(substr($requestPath, 0, $panjang), $acuan) === 0
                ? substr($requestPath, 0, $panjang)
                : $acuan;
        }

        return ($https ? 'https' : 'http') . '://' . $host . $dir . '/';
    }

    /**
     * Allowed Hostnames in the Site URL other than the hostname in the baseURL.
     * If you want to accept multiple Hostnames, set this.
     *
     * E.g.,
     * When your site URL ($baseURL) is 'http://example.com/', and your site
     * also accepts 'http://media.example.com/' and 'http://accounts.example.com/':
     *     ['media.example.com', 'accounts.example.com']
     *
     * @var list<string>
     */
    public array $allowedHostnames = [];

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     *
     * Typically, this will be your `index.php` file, unless you've renamed it to
     * something else. If you have configured your web server to remove this file
     * from your site URIs, set this variable to an empty string.
     */
    public string $indexPage = 'index.php';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     *
     * This item determines which server global should be used to retrieve the
     * URI string. The default setting of 'REQUEST_URI' works for most servers.
     * If your links do not seem to work, try one of the other delicious flavors:
     *
     *  'REQUEST_URI': Uses $_SERVER['REQUEST_URI']
     * 'QUERY_STRING': Uses $_SERVER['QUERY_STRING']
     *    'PATH_INFO': Uses $_SERVER['PATH_INFO']
     *
     * WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
     */
    public string $uriProtocol = 'REQUEST_URI';

    /*
    |--------------------------------------------------------------------------
    | Allowed URL Characters
    |--------------------------------------------------------------------------
    |
    | This lets you specify which characters are permitted within your URLs.
    | When someone tries to submit a URL with disallowed characters they will
    | get a warning message.
    |
    | As a security measure you are STRONGLY encouraged to restrict URLs to
    | as few characters as possible.
    |
    | By default, only these are allowed: `a-z 0-9~%.:_-`
    |
    | Set an empty string to allow all characters -- but only if you are insane.
    |
    | The configured value is actually a regular expression character group
    | and it will be used as: '/\A[<permittedURIChars>]+\z/iu'
    |
    | DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
    |
    */
    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     *
     * The Locale roughly represents the language and location that your visitor
     * is viewing the site from. It affects the language strings and other
     * strings (like currency markers, numbers, etc), that your program
     * should run under for this request.
     */
    public string $defaultLocale = 'en';

    /**
     * --------------------------------------------------------------------------
     * Negotiate Locale
     * --------------------------------------------------------------------------
     *
     * If true, the current Request object will automatically determine the
     * language to use based on the value of the Accept-Language header.
     *
     * If false, no automatic detection will be performed.
     */
    public bool $negotiateLocale = false;

    /**
     * --------------------------------------------------------------------------
     * Supported Locales
     * --------------------------------------------------------------------------
     *
     * If $negotiateLocale is true, this array lists the locales supported
     * by the application in descending order of priority. If no match is
     * found, the first locale will be used.
     *
     * IncomingRequest::setLocale() also uses this list.
     *
     * @var list<string>
     */
    public array $supportedLocales = ['en'];

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     *
     * The default timezone that will be used in your application to display
     * dates with the date helper, and can be retrieved through app_timezone()
     *
     * @see https://www.php.net/manual/en/timezones.php for list of timezones
     *      supported by PHP.
     */
    public string $appTimezone = 'UTC';

    /**
     * --------------------------------------------------------------------------
     * Default Character Set
     * --------------------------------------------------------------------------
     *
     * This determines which character set is used by default in various methods
     * that require a character set to be provided.
     *
     * @see http://php.net/htmlspecialchars for a list of supported charsets.
     */
    public string $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * Force Global Secure Requests
     * --------------------------------------------------------------------------
     *
     * If true, this will force every request made to this application to be
     * made via a secure connection (HTTPS). If the incoming request is not
     * secure, the user will be redirected to a secure version of the page
     * and the HTTP Strict Transport Security (HSTS) header will be set.
     */
    public bool $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     *
     * If your server is behind a reverse proxy, you must whitelist the proxy
     * IP addresses from which CodeIgniter should trust headers such as
     * X-Forwarded-For or Client-IP in order to properly identify
     * the visitor's IP address.
     *
     * You need to set a proxy IP address or IP address with subnets and
     * the HTTP header for the client IP address.
     *
     * Here are some examples:
     *     [
     *         '10.0.1.200'     => 'X-Forwarded-For',
     *         '192.168.5.0/24' => 'X-Real-IP',
     *     ]
     *
     * @var array<string, string>
     */
    public array $proxyIPs = [];

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     *
     * Enables the Response's Content Secure Policy to restrict the sources that
     * can be used for images, scripts, CSS files, audio, video, etc. If enabled,
     * the Response object will populate default values for the policy from the
     * `ContentSecurityPolicy.php` file. Controllers can always add to those
     * restrictions at run time.
     *
     * For a better understanding of CSP, see these documents:
     *
     * @see http://www.html5rocks.com/en/tutorials/security/content-security-policy/
     * @see http://www.w3.org/TR/CSP/
     */
    public bool $CSPEnabled = false;
}
