// Sarkari - Frontend JavaScript

// Auto-dismiss flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[role="alert"]').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() { alert.remove(); }, 500);
        }, 5000);
    });
});

// Blueprint status polling (used on generating page)
function pollBlueprintStatus(blueprintId, onReady) {
    const interval = setInterval(function() {
        fetch('/blueprint/status/' + blueprintId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.ready) {
                    clearInterval(interval);
                    if (onReady) onReady(data);
                    else window.location.href = '/blueprint/view/' + blueprintId;
                } else if (data.status === 'failed') {
                    clearInterval(interval);
                    window.location.href = '/dashboard';
                }
            })
            .catch(function() {});
    }, 3000);
}
