$(function () {
    function formatCurrency(value) {
        if (value === null || value === undefined || value === '') {
            return '—';
        }
        return new Intl.NumberFormat('en-AU', {
            style: 'currency',
            currency: 'AUD',
            maximumFractionDigits: 0
        }).format(value);
    }

    function renderSummary(data) {
        const fields = [
            { label: 'Participants', value: data.participants },
            { label: 'Providers', value: data.providers },
            { label: 'Invoices Pending', value: data.invoices_pending },
            { label: 'Invoices Paid', value: data.invoices_paid },
            { label: 'Total Budget', value: formatCurrency(data.total_budget) },
            { label: 'Spent', value: formatCurrency(data.total_spent) },
            { label: 'Committed', value: formatCurrency(data.total_committed) }
        ];

        const $summary = $('#summary');
        $summary.empty();
        fields.forEach((field) => {
            $summary.append(
                `<div>
                    <span class="label">${field.label}</span>
                    <strong>${field.value ?? '—'}</strong>
                </div>`
            );
        });
    }

    function renderRoles(data) {
        const $roles = $('#roles');
        $roles.empty();
        data.forEach((role) => {
            const items = role.capabilities
                .map((item) => `<li>${item}</li>`)
                .join('');
            $roles.append(`
                <div class="role-card">
                    <h6>${role.role}</h6>
                    <ul class="small">${items}</ul>
                </div>
            `);
        });
    }

    function renderAlerts(data) {
        const $alerts = $('#alerts');
        $alerts.empty();
        if (!data.length) {
            $alerts.append('<p class="text-muted">No alerts available.</p>');
            return;
        }
        data.forEach((alert) => {
            const statusClass = alert.status === 'warning' ? 'alert-warning' : 'alert-info';
            $alerts.append(`
                <div class="alert ${statusClass}">
                    <strong>${alert.type}</strong>
                    <p class="mb-0">${alert.message}</p>
                </div>
            `);
        });
    }

    function getApiUrl(endpoint) {
        const url = new URL('api.php', window.location.href);
        url.searchParams.set('endpoint', endpoint);
        return url.toString();
    }

    function loadSummary() {
        $.getJSON(getApiUrl('summary'), renderSummary);
    }

    function loadAlerts() {
        $.getJSON(getApiUrl('alerts'), renderAlerts);
    }

    function loadRoles() {
        $.getJSON(getApiUrl('roles'), renderRoles);
    }

    function renderPreview(endpoint, data) {
        const $preview = $('#api-preview');
        const payload = JSON.stringify(
            {
                endpoint,
                fetched_at: new Date().toISOString(),
                data
            },
            null,
            2
        );
        $preview.text(payload);
    }

    function loadPreview(endpoint) {
        $.getJSON(getApiUrl(endpoint))
            .done((data) => {
                renderPreview(endpoint, data);
            })
            .fail(() => {
                renderPreview(endpoint, {
                    error: 'Unable to reach api.php. Check the server base path and routing.'
                });
            });
    }

    $('#load-summary').on('click', loadSummary);
    $('#load-alerts').on('click', loadAlerts);
    $('.preview-btn').on('click', function () {
        const endpoint = $(this).data('preview-endpoint');
        loadPreview(endpoint);
    });

    loadSummary();
    loadAlerts();
    loadRoles();
    loadPreview('summary');
});
