<?php
// mikrotikrest.php
// All functions here assume the constant MIKROTIK_IP is defined (in status.php).

require_once __DIR__ . '/define.php';

/**
 * Perform a GET request against the Mikrotik REST API and return decoded JSON.
 *
 * @param string $endpoint e.g. "tool/netwatch" or "ip/dhcp-server/lease"
 * @return array|null
 */
function mikrotik_rest_get(string $endpoint): ?array
{
    foreach (['MIKROTIK_IP', 'MIKROTIK_USER', 'MIKROTIK_PASS'] as $const) {
        if (!defined($const)) {
            throw new RuntimeException($const . ' is not defined.');
        }
    }

    $baseUrl = 'http://' . MIKROTIK_IP . '/rest/';
    $url     = $baseUrl . ltrim($endpoint, '/');

    // Build headers (Basic Auth)
    $auth    = base64_encode(MIKROTIK_USER . ':' . MIKROTIK_PASS);
    $headers = "Accept: application/json\r\n" .
               "Authorization: Basic {$auth}\r\n";

    $contextOptions = [
        'http' => [
            'method'  => 'GET',
            'header'  => $headers,
            'timeout' => 5,
        ],
        'ssl' => [
            // accept any certificate (unsafe for production)
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    $context = stream_context_create($contextOptions);
    $json    = @file_get_contents($url, false, $context);

    if ($json === false) {
        return null;
    }

    $data = json_decode($json, true);
    if (!is_array($data)) {
        return null;
    }

    return $data;
}


/**
 * Return all netwatch entries that are online (status = "up", not disabled).
 *
 * @return array
 */
function get_netwatch_online(): array
{
    $data = mikrotik_rest_get('tool/netwatch');
    if (!is_array($data)) {
        return [];
    }

    $online = [];
    foreach ($data as $entry) {
        $status   = $entry['status']   ?? '';
        $disabled = $entry['disabled'] ?? 'false';

        if ($status === 'up' && $disabled === 'false') {
            $online[] = $entry;
        }
    }

    return $online;
}

/**
 * Return all active DHCP leases where:
 *  - status = "bound"
 *  - server starts with "dhcp_team"
 *
 * @return array
 */
function get_dhcp_team_leases(): array
{
    $data = mikrotik_rest_get('ip/dhcp-server/lease');
    if (!is_array($data)) {
        return [];
    }

    $result = [];
    foreach ($data as $entry) {
        $status = $entry['status'] ?? '';
        $server = $entry['server'] ?? '';

        if ($status !== 'bound') {
            continue;
        }

        // server name must start with "dhcp_team"
        if (strpos($server, 'dhcp_team') !== 0) {
            continue;
        }

        $result[] = $entry;
    }

    return $result;
}
