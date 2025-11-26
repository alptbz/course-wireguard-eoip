<?php

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/mikrotikrest.php';

$participants    = get_participants();
$netwatchOnline  = get_netwatch_online();
$dhcpTeamLeases  = get_dhcp_team_leases();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Completion Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        #status-footer {
            position: fixed;
            bottom: 6px;
            right: 10px;
            font-size: 12px;
            color: #555;
            opacity: 0.7;
            font-family: sans-serif;
        }

.fixed-vh {
  min-height: calc(100vh - 96px);
}
    </style>
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="row fixed-vh">
        <!-- Left box: list of participants that concluded the lab -->
        <div class="col-md-6 mb-4 mb-md-0 d-flex">
            <div class="card shadow-sm w-100 h-100">
                <div class="card-header text-center">
                    <h4 class="mb-0">Participants who completed the lab</h4>
                </div>
                <div class="card-body d-flex flex-column">
                    <?php if (empty($participants)): ?>
                        <div class="alert alert-info mt-2">
                            No participants have completed the lab yet.
                        </div>
                    <?php else: ?>
                        <p class="mb-3">
                            The following participants have successfully concluded the lab:
                        </p>
                        <div class="table-responsive flex-grow-1">
                            <table class="table table-striped table-bordered align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>IP</th>
                                    <th>Completed at</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($participants as $index => $p): ?>
                                    <tr>
                                        <td><?php echo h($p['name']); ?></td>
                                        <td><?php echo h($p['ip']); ?></td>
                                        <td><?php echo h($p['created_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center small">
                    <a href="index.php" class="btn btn-secondary btn-sm">Back to registration</a>
                </div>
            </div>
        </div>

        <!-- Right box: Mikrotik status info -->
        <div class="col-md-6 d-flex">
            <div class="card shadow-sm w-100 h-100">
                <div class="card-header text-center">
                    <h4 class="mb-0">Network / Lab Infrastructure</h4>
                </div>
                <div class="card-body">
                    <!-- WireGuard UP section -->
                    <h5 class="mb-3">WireGuard UP</h5>
                    <?php if (empty($netwatchOnline)): ?>
                        <p class="text-muted">
                            No WireGuard netwatch entries are currently online (or data is unavailable).
                        </p>
                    <?php else: ?>
                        <ul class="list-group mb-4">
                            <?php foreach ($netwatchOnline as $entry): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?php echo h($entry['name'] ?? 'WireGuard'); ?>
                                        <!--<span class="text-muted small">
                                            (<?php echo h($entry['host'] ?? ''); ?>)
                                        </span>-->
                                    </span>
                                    <span class="badge bg-success">UP</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- EoIP up and DHCP Lease success section -->
                    <h5 class="mb-3">EoIP up and DHCP Lease success</h5>
                    <?php if (empty($dhcpTeamLeases)): ?>
                        <p class="text-muted">
                            No active DHCP leases found for servers starting with <code>dhcp_team</code>.
                        </p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>IP</th>
                                    <th>MAC</th>
                                    <th>Hostname</th>
                                    <th>Expires</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($dhcpTeamLeases as $lease): ?>
                                    <tr>
                                        <td><?php echo h($lease['active-address'] ?? $lease['address'] ?? ''); ?></td>
                                        <td><?php echo h($lease['active-mac-address'] ?? $lease['mac-address'] ?? ''); ?></td>
                                        <td><?php echo h($lease['host-name'] ?? ''); ?></td>
                                        <td><?php echo h($lease['expires-after'] ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="status-footer"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const reloadInterval = 10000;
  const footer = document.getElementById("status-footer");

  const loadTime = new Date(); // fixed timestamp

  function updateFooter() {
    const now = new Date();
    const remaining = reloadInterval - (now - loadTime);
    const secs = Math.max(0, Math.ceil(remaining / 1000));

    footer.textContent =
      "Updated: " + loadTime.toLocaleTimeString() +
      " | Reload in: " + secs + "s";
  }

  updateFooter();
  setInterval(updateFooter, 200);

  setTimeout(() => location.reload(), reloadInterval);
</script>
</body>
</html>
