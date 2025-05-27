<template>
  <Head title="Expense Report By Date" />
  <AppLayout title="Expense Report By Date">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between"
          >
            <h1 class="font-semibold text-3xl text-primary">Expense Report By Date</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form
              @submit.prevent="report.get(route('expense.report'), report)"
            >
              <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                    <label
                      for="from_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >From Date
                    </label>
                    <div class="mt-2">
                      <input
                        id="from_date"
                        name="from_date"
                        type="text" ref="from_date"
                        autocomplete="from_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"

                        v-model="report.from_date"
                      />
                    </div>
                  </div>

                  <div>
                    <label
                      for="to_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >To Date
                    </label>
                    <div class="mt-2">
                      <input
                        id="to_date"
                        name="to_date"
                        type="text" ref="to_date"
                        autocomplete="to_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"

                        v-model="report.to_date"
                      />
                    </div>
                  </div>
                  <div class="mt-2">
                    <label
                      for="to_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >&nbsp;
                    </label>
                    <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" >
                    Search
                  </button>
                    <button
                  class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0" >
                    Print
                  </button>
                  </div>
              </div>
            </form>

            </div>
          </div>
          <div class="mt-6 flow-root" id="printData">

            <div
            style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
            <div class="print-only">
              <div style="display: flex;flex-direction: column; align-items: center;justify-content: center;">
                  <img style="width:100px;object-fit: contain; margin-bottom: 16px;" alt="">
                  <p style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 2px;">Chiniot
                      General Hospital</p>
                      <p v-if="projectType == 'hms'" style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Nawaz Town
                      Sarghoda Road, Faisalabad</p>
                   <p v-else style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">209 Jinnah Colony , Faisalabad</p>

                  <p v-if="projectType == 'hms'" style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph:
                      041-8848060 | 8787231</p>
                      <p v-else style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph:
                      041-2618764 | 2634890</p>
              </div>
              <div
                  style="display: flex;align-items: center;justify-content: space-between;flex: initial;margin-top: 20px !important;width: 100%;margin: 10px auto;">
                  <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">User</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;">All</p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">Date & Time</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;">{{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getFullYear() }} {{new Date().toTimeString().slice(0, 5)}}</p>
                      </div>
                  </div>
              </div>
              <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 10px auto">
                  <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">From</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;"> {{ new Date(report?.from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                        </p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">To</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;">  {{ new Date(report?.to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}  </p>
                      </div>
                  </div>
              </div>
              <div>

                  <p
                      style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 20px;margin-top: 20px;">Expense Report By Date</p>
              </div>
            </div>
            <div class="ring-1 ring-gray-300 border border-gray-300   ">
              <div class="table-wrap block  ">
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 ">
                    <thead>
                      <tr class="divide-x divide-gray-300">
                        <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Date
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap w-44 text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Description
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Amount
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Payment Method
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Receipt no
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Authority
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-y border-gray-300 sticky top-0 z-10"
                      >
                        Notes
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      class="divide-x divide-gray-300"
                      v-for="(expense, index) in reports"
                      :key="index"
                      :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                    >
                      <td
                        class="whitespace-nowrap relative text-gray-900"
                      >
                      {{ new Date(expense.date).toLocaleDateString('en-GB').replace(/\//g, '-') }}

                      </td>
                      <td
                        class="whitespace-normal w-44 relative text-gray-900"
                      >
                        {{ expense.description }}
                      </td>
                      <td
                        class="whitespace-nowrap relative text-gray-900"
                      >
                        {{ expense.amount }}
                      </td>
                      <td
                        class="whitespace-nowrap relative text-gray-900"
                      >
                        {{ expense.payment_method }}
                      </td>
                      <td
                        class="whitespace-nowrap relative text-gray-900"
                      >
                        {{ expense.receipt_no }}
                      </td>
                      <td
                        class="whitespace-nowrap relative text-gray-900"
                      >
                        {{ expense.approved_by }}
                      </td>
                      <td
                        class="whitespace-nowrap relative text-gray-900"
                      >
                        {{ expense?.notes }}
                      </td>
                    </tr>
                  </tbody>

                  <tfoot>
                    <tr
                      class="divide-x divide-gray-300 bg-secondary text_white border-y border-gray-300"
                    >
                      <td
                        class="whitespace-nowrap relative border-y border-gray-300"
                        colspan="2"
                      >
                        Total
                      </td>
                      <td
                        class="whitespace-nowrap relative border-y border-gray-300 "
                      >
                        {{ sum(reports) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-y border-gray-300 "
                        colspan="4"
                      >
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>

            </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

    <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const props = defineProps({
  reports: Object,
  from_date: String,
  to_date: String,
});

const report = useForm({
  from_date: props?.from_date || null,
  to_date: props?.to_date || null,
});
onMounted(() => {

const flatpickrOptions = (defaultDate) => ({
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    allowInput: true, // Allows manual typing
    clickOpens: true, // Ensures the picker still works
    defaultDate: defaultDate || new Date(), // Use provided default date or current date
});

flatpickr(to_date.value, flatpickrOptions(report.to_date));
flatpickr(from_date.value, flatpickrOptions(report.from_date));

});
const print = () => {
  var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();

  printWindow.close();
  window.location.reload();
}

const sum = (reports) => {
  let total = 0;
  reports.map(res => {
      total = +total + +res.amount
  });
  return total;
}
</script>
<script>
export default {
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
<style>
        td {
            text-align: left;
            padding: 10px 8px;
            font-size: 14px;
        }

        th {
            font-size: 14px;
            font-weight: 600;
            color: black;
            padding: 4px 8px;
            text-align: center;
            height: 24px;
        }

        table {
            width: 100%;
            height: auto;
            border-collapse: collapse;
        }

        .total_row {
            border-left: 1px solid;
            border-right: 1px solid;
        }

        .total_row td {
            border: none;
            font-weight: 600;
        }

        table tfoot tr {
            border: 1px solid;
        }

        table tfoot td {
            border: none;
            font-size: 14px;
            font-weight: 600;
            text-align: left;
        }
        .print-only {
            display: none;
        }
        table th, table tfoot td{
          padding: 12px 14px;
          font-size:16px;
        }
        table td{
          padding: 10px 12px;
          font-size: 14px;
        }
        @media only screen and (min-width: 1600px) {
          table th, table tfoot td{
            padding: 14px 16px;
            font-size:18px;
          }
        }
        .text_white{
          color: #fff;
        }
        .table_height{
          max-height: calc(100vh - 60px);
        }
        .whitespace_nowrap{
          white-space: nowrap;
        }
        /* CSS to show the element only when printing */
        @media print {
            .print-only {
                display: block;
            }
            table th, table tfoot td{
          padding: 4px;
          font-size: 12px !important;
          line-height: 12px !important;
          }
          table td{
          padding: 4px;
          font-size: 10px !important;
          line-height: 12px !important;
          }
          .text_white{
            color: #000 !important;
          }
          .table_height{
            max-height: max-content;
          }
          ::-webkit-scrollbar {
            display: none;
          }
        .whitespace_nowrap{
          white-space: normal !important;
        }
        }
    </style>
