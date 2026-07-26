<script>
    const mmTabTitles = {
        machines: 'Machines',
        logs: 'Daily Logs',
        alarms: 'Alarms',
        maintenance: 'Maintenance Schedule',
        calibration: 'Calibration',
        sensors: 'IoT Sensors'
    };

    function switchMmTab(tab, btn) {
        document.querySelectorAll('.mm-tab-panel').forEach(panel => panel.classList.add('hidden'));
        document.getElementById(`mm-tab-${tab}`).classList.remove('hidden');

        document.querySelectorAll('.mm-tab-btn').forEach(b => {
            b.classList.remove('text-slate-700', 'border-b-2', 'border-brand-600');
            b.classList.add('text-slate-500');
        });
        btn.classList.remove('text-slate-500');
        btn.classList.add('text-slate-700', 'border-b-2', 'border-brand-600');

        document.getElementById('mmSectionTitle').textContent = mmTabTitles[tab];
    }

    function toggleMachineDetails(id) {
        const detailsRow = document.getElementById(`machine-details-${id}`);
        if (detailsRow) {
            detailsRow.classList.toggle('hidden');
        }
    }
</script>
