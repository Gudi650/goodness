<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Inventory — Goodness Group</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="min-h-screen md:flex">
    <aside class="hidden md:block w-64 bg-white border-r border-slate-200 p-6">
      <div class="text-sm font-semibold">Goodness Group</div>
      <nav class="mt-6 space-y-1"><a href="/dashboard" class="block px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a><a href="/inventory" class="block px-3 py-2 text-sm bg-green-50 text-green-700 rounded-l-md">Inventory</a></nav>
    </aside>

    <main class="flex-1 p-6">
      <div class="mb-6"><h1 class="text-2xl font-semibold">Inventory & Procurement</h1><p class="text-sm text-slate-500">Products, suppliers and purchase orders</p></div>

      <div class="bg-white border border-slate-200 rounded-lg p-4">
        <div class="flex gap-2 mb-4"><button id="tabProducts" class="px-3 py-2 bg-green-50 text-green-700 rounded-md">Products</button><button id="tabSuppliers" class="px-3 py-2 text-slate-600 rounded-md">Suppliers</button><button id="tabPO" class="px-3 py-2 text-slate-600 rounded-md">Purchase Orders</button></div>
        <div id="invContent"></div>
      </div>
    </main>
  </div>

  <script>
    const products = [ { id:1, name:'Widget A', sku:'W-A', stock:120 }, { id:2, name:'Widget B', sku:'W-B', stock:45 } ];
    function renderProducts(){ return `
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-slate-50">
            <tr>
              <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Product</th>
              <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">SKU</th>
              <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Stock</th>
              <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-100">
            ${products.map(p=>`<tr class="hover:bg-slate-50"><td class="px-4 py-3 text-sm">${p.name}</td><td class="px-4 py-3 text-sm">${p.sku}</td><td class="px-4 py-3 text-sm text-right">${p.stock}</td><td class="px-4 py-3 text-sm text-center"><button class="px-2 py-1 border border-slate-200 rounded-md">View</button></td></tr>`).join('')}
          </tbody>
        </table>
      </div>
    ` }

    document.getElementById('tabProducts').addEventListener('click', ()=> document.getElementById('invContent').innerHTML = renderProducts());
    document.getElementById('tabSuppliers').addEventListener('click', ()=> document.getElementById('invContent').innerHTML = '<p class="text-sm text-slate-500">No suppliers yet.</p>');
    document.getElementById('tabPO').addEventListener('click', ()=> document.getElementById('invContent').innerHTML = '<p class="text-sm text-slate-500">No purchase orders yet.</p>');
    document.getElementById('tabProducts').click();
  </script>
</body>
</html>
