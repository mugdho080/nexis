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

    function loadRoles() {
        $.getJSON(getApiUrl('roles'), renderRoles);
    }

    function updateBudgetProgress() {
        $('.summary-card, .category-card').each(function () {
            const $card = $(this);
            const total = Number($card.data('total')) || 0;
            const spent = Number($card.data('spent')) || 0;
            const quarantined = Number($card.data('quarantined')) || 0;
            const spentPercent = total > 0 ? Math.min((spent / total) * 100, 100) : 0;
            const quarantinePercent = total > 0 ? Math.min((quarantined / total) * 100, 100) : 0;

            $card.find('.spent-bar').css('width', `${spentPercent}%`);
            $card.find('.quarantine-bar').css('width', `${quarantinePercent}%`);
        });
    }

    function updateQuarantineTotal() {
        let total = 0;
        $('#quarantine-table td[data-amount]').each(function () {
            total += Number($(this).data('amount')) || 0;
        });
        $('#quarantine-total').text(
            new Intl.NumberFormat('en-AU', {
                style: 'currency',
                currency: 'AUD',
                maximumFractionDigits: 0
            }).format(total)
        );
    }

    function bindQuarantineForm() {
        $('#quarantine-form').on('submit', function (event) {
            event.preventDefault();
            const $form = $(this);
            const provider = $form.find('[name="provider"]').val();
            const category = $form.find('[name="category"]').val();
            const amount = Number($form.find('[name="amount"]').val());
            const service = $form.find('[name="service"]').val();

            if (!provider || !category || !amount || !service) {
                return;
            }

            $('#quarantine-table').prepend(`
                <tr>
                    <td>${provider}</td>
                    <td>${category}</td>
                    <td data-amount="${amount}">${formatCurrency(amount)}</td>
                    <td><span class="badge text-bg-success">Active</span></td>
                </tr>
            `);

            $form.trigger('reset');
            updateQuarantineTotal();
        });
    }

    function bindSpendingCheck() {
        $('#run-spending-check').on('click', function () {
            const now = new Date().toLocaleTimeString('en-AU', { hour: '2-digit', minute: '2-digit' });
            $('#alerts').prepend(`
                <div class="alert alert-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>NDIS-22019</strong>
                        <span class="badge text-bg-secondary">Core</span>
                    </div>
                    <p class="mb-0">Overspend Risk — Automated check at ${now} flagged a 30-day deficit.</p>
                </div>
            `);
        });
    }

    function bindParticipantSearch() {
        $('#participant-search').on('input', function () {
            const term = $(this).val().toLowerCase();
            $('#participant-table tr').each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(term) !== -1);
            });
        });
    }

    function bindPriceSearch() {
        $('#price-search').on('input', function () {
            const term = $(this).val().toLowerCase();
            $('#price-table tr').each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(term) !== -1);
            });
        });
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
    $('#load-alerts').on('click', function () {
        const $target = $('#spending-alerts-section');
        if ($target.length) {
            $('html, body').animate(
                { scrollTop: $target.offset().top - 20 },
                500
            );
        }
    });
    $('.preview-btn').on('click', function () {
        const endpoint = $(this).data('preview-endpoint');
        loadPreview(endpoint);
    });

    loadSummary();
    loadRoles();
    loadPreview('summary');
    updateBudgetProgress();
    updateQuarantineTotal();
    bindQuarantineForm();
    bindSpendingCheck();
    bindParticipantSearch();
    bindPriceSearch();
});
