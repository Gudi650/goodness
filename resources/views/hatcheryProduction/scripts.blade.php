<script>
        const phTabTitles = {
            flocks: 'Breeder Flocks',
            eggs: 'Egg Collection',
            batches: 'Incubation Batches',
            hatch: 'Hatch Registration',
            allocation: 'Chick Allocation',
            costing: 'Batch Costing'
        };

        function switchPhTab(tab, btn) {
            document.querySelectorAll('.ph-tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`ph-tab-${tab}`).classList.remove('hidden');

            document.querySelectorAll('.ph-tab-btn').forEach(b => {
                b.classList.remove('text-slate-700', 'border-b-2', 'border-brand-600');
                b.classList.add('text-slate-500');
            });
            btn.classList.remove('text-slate-500');
            btn.classList.add('text-slate-700', 'border-b-2', 'border-brand-600');

            document.getElementById('phSectionTitle').textContent = phTabTitles[tab];
        }

        function togglePhDetails(id) {
            const detailsRow = document.getElementById(`ph-details-${id}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
            }
        }
    </script>
