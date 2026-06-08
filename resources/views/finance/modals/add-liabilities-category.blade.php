<!-- Liability Category Modal -->
<div id="addLiabilityCategoryModal" class="hidden ">

    <form id="liabilityCategoryForm" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-slate-700">Category</label>
        <input type="text" name="category" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" placeholder=" eg: Operationals,Payroll, Emergency " required>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700">Description</label>
        <textarea name="description" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" rows="3" placeholder="Enter description"></textarea>
      </div>

      
      <div class="flex justify-end gap-3 pt-4">
        <button type="button" onclick="closeModal()"
            class="px-4 py-2 border border-slate-300 rounded hover:bg-slate-100">Cancel</button>
        <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg">Save</button>
      </div>
    </form>

</div>