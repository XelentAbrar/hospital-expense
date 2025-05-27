<template>
  <Head title="Create Expenses" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Expenses
          </h1>
            <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('expenses.update', { id: form.id }))
                  : form.post(route('expenses.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('expenses.update', { id: form.id }))
                : form.post(route('expenses.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                  <div class="">
                    <label
                      for="receipt_no"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Receipt No

                      </label
                    >
                      <input
                        id="receipt_no"
                        name="receipt_no"
                        type="text"
                        autocomplete="receipt_no"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"

                        v-model="form.receipt_no"
                      />
                    <InputError
                      v-if="form?.errors?.receipt_no"
                      :message="form?.errors?.receipt_no"
                    />
                  </div>
                  <div class="">
                    <label
                      for="date"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="date"
                        name="date"
                        type="date"
                        autocomplete="date"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        step="0.01"
                        v-model="form.date" disabled
                      />
                    <InputError
                      v-if="form?.errors?.date"
                      :message="form?.errors?.date"
                    />
                  </div>
                  <div class="">
                    <label
                      for="amount"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Amount
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="amount"
                        name="amount"
                        type="text"
                        autocomplete="amount"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        step="0.01"
                        v-model="form.amount"
                      />
                    <InputError
                      v-if="form?.errors?.amount"
                      :message="form?.errors?.amount"
                    />
                  </div>
                  <div class="md:col-span-3">
                    <label
                      for="description"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Description
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="description"
                        name="description"
                        type="text"
                        autocomplete="description"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        step="0.01"
                        v-model="form.description"
                      />
                    <InputError
                      v-if="form?.errors?.description"
                      :message="form?.errors?.description"
                    />
                  </div>
                  <div class="">
                    <label
                      for="payment_method"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Payment Method
                      </label>
                      <input
                        id="payment_method"
                        name="payment_method"
                        type="text"
                        autocomplete="payment_method"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        step="0.01"
                        v-model="form.payment_method"
                      />
                    <InputError
                      v-if="form?.errors?.payment_method"
                      :message="form?.errors?.payment_method"
                    />
                  </div>

                  <div class="">
                    <label
                      for="approved_by"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Authority

                      </label
                    >
                      <input
                        id="approved_by"
                        name="approved_by"
                        type="text"
                        autocomplete="approved_by"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        step="0.01"
                        v-model="form.approved_by"
                      />
                    <InputError
                      v-if="form?.errors?.approved_by"
                      :message="form?.errors?.approved_by"
                    />
                  </div>
                  <div class="">
                    <label
                      for="coa_id"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Account Head

                      </label
                    >
                      <multiselect
                        v-model="selectedCoa"
                        :options="coa"
                        @update:modelValue="onCoaSelect"
                        label="acc_desc"
                        track-by="id"
                        placeholder="Select a Account Head"
                      ></multiselect>
                    <InputError
                      v-if="form?.errors?.coa_id"
                      :message="form?.errors?.coa_id"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-3">
                    <label
                      for="notes"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Notes

                      </label
                    >
                    <div class="mt-2">
                      <textarea
                        id="notes"
                        name="notes"
                        type="text"
                        autocomplete="notes"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        step="0.01"
                        v-model="form.notes"
                      ></textarea>
                    </div>
                    <InputError
                      v-if="form?.errors?.notes"
                      :message="form?.errors?.notes"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('expenses.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit" :disabled="form.processing"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                  Save
                </button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
  <script setup>
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, defineComponent, watch } from "vue";
import InputError from "../../Components/InputError.vue";
import {
  Switch,
  SwitchDescription,
  SwitchGroup,
  SwitchLabel,
} from "@headlessui/vue";

// const props = defineProps({
//   expenses: Object,
// });
const props = defineProps({
  receipt_no: String,
  date: String,
  coa:Array,
  expenses: {
    type: Object,
    default: () => ({}),
  },
});
const form = useForm({
  id: props?.expenses?.id || null,
  // date: props?.expenses?.date || null,
  date: props.expenses?.date || props.date || null,
  description: props?.expenses?.description || null,
  amount: props?.expenses?.amount || null,
  payment_method: props?.expenses?.payment_method || null,
  // receipt_no: props?.expenses?.receipt_no || null,
  receipt_no: props.expenses?.receipt_no || props.receipt_no || null,
  approved_by: props?.expenses?.approved_by || 'Mian Sikandar SB' ,
  notes: props?.expenses?.notes || null,
  coa_id: props?.expenses?.coa_id || null,
});


const selectedCoa = ref(null);
if (props?.expenses) {
  const coas = props.coa.find(
    (c) => c.id === props.expenses.coa_id
  );
  if (coas) {
    selectedCoa.value = coas;
  }
}

const onCoaSelect = async (selectedCoa) => {
  form.coa_id = selectedCoa?.id ?? null;
};


const cancel = () => {
  Inertia.visit(route("expenses.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
