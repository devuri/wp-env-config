<?php

namespace Urisoft\App\Http;

class AppHostManager implements HostInterface
{
    /**
     * Determines if the current request is made over HTTPS.
     *
     * @return bool True if the request is over HTTPS, false otherwise.
     */
    public function is_https_secure(): bool
    {
        if ( isset( $_SERVER['HTTPS'] ) && filter_var( $_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN ) ) {
            return true;
        }

        // Check for the 'X-Forwarded-Proto' header in case of reverse proxy setups.
        if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves the sanitized HTTP host if available, otherwise a default value.
     *
     * @return string The sanitized host name or a default value.
     */
    public function get_http_host(): string
    {
        if ( isset( $_SERVER['HTTP_HOST'] ) ) {
            $httpHost = $this->_sanitize_http_host( $_SERVER['HTTP_HOST'] );

            if ( $httpHost ) {
                return strtolower( rtrim( $httpHost, '/' ) );
            }
        }

        return 'default_domain.com';
    }

    /**
     * Extracts the host domain and determines the protocol prefix.
     *
     * @return array An associative array with 'prefix' (protocol) and 'domain' (host domain).
     */
    public function get_server_host(): array
    {
        $host_domain = $this->get_http_host();

        // Remove port information if present
        $portPosition = strrpos( $host_domain, ':' );
        if ( false !== $portPosition ) {
            $host_domain = substr( $host_domain, 0, $portPosition );
        }

        $prefix = $this->is_https_secure() ? 'https' : 'http';

        return [
            'prefix' => $prefix,
            'domain' => $host_domain,
        ];
    }

    /**
     * Constructs the full request URL based on the current protocol and app host.
     *
     * @return null|string The full request URL or null if the app host is not available.
     */
    public function get_request_url(): ?string
    {
        $isHttps  = $this->is_https_secure();
        $app_host = $this->get_http_host();

        if ( \is_null( $app_host ) ) {
            return null;
        }

        $protocol = $isHttps ? 'https' : 'http';

        return filter_var( $protocol . '://' . $app_host, FILTER_SANITIZE_URL );
    }

    /**
     * Sanitizes the HTTP host.
     *
     * @param string $httpHost The HTTP host to sanitize.
     *
     * @return null|string The sanitized host or null if invalid.
     */
    protected function _sanitize_http_host( string $httpHost ): ?string
    {
        $sanitizedHost = filter_var( $httpHost, FILTER_SANITIZE_URL );

        if ( preg_match( '/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $sanitizedHost ) ) {
            return $sanitizedHost;
        }

        return null;
    }
}
