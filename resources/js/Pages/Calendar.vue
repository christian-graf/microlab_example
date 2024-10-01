<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

/**
 * Properties of this vue page.
 *
 * @type {Prettify<Readonly<ExtractPropTypes<{reminders: {default(): [], type: ArrayConstructor}}>>>}
 */
const props = defineProps({
    reminders: {
        type: Array,
        default() {
            return [];
        }
    }
});

/**
 * Current loaded or entered reminder.
 *
 * @type {Ref<UnwrapRef<{month: null, description: null, interval: null, id: null, day: null}>, UnwrapRef<{month: null, description: null, interval: null, id: null, day: null}> | {month: null, description: null, interval: null, id: null, day: null}>}
 */
const reminder = ref({
    id: null,
    day: null,
    month: null,
    description: null,
    interval: null,
});

/**
 * List of available intervals
 *
 * @type {Ref<UnwrapRef<[{text: string, value: string},{text: string, value: string},{text: string, value: string},{text: string, value: string},{text: string, value: string}]>, UnwrapRef<[{text: string, value: string},{text: string, value: string},{text: string, value: string},{text: string, value: string},{text: string, value: string}]> | [{text: string, value: string},{text: string, value: string},{text: string, value: string},{text: string, value: string},{text: string, value: string}]>}
 */
const reminderIntervals = ref([
    { text: '1 Day', value: '1D' },
    { text: '2 Days', value: '2D' },
    { text: '4 Days', value: '4D' },
    { text: '1 Week', value: '1W' },
    { text: '2 Weeks', value: '2W' },
])

/**
 * Last error message.
 *
 * @type {String}
 */
let errorMessage = ref(null);

/**
 * Clears the current loaded reminder list.
 */
function clearReminders() {
    while (props.reminders.length > 0) {
        props.reminders.pop();
    }
}

/**
 * Set the reminder list.
 *
 * @param reminders
 */
function setReminders(reminders) {
    clearReminders();

    for (const reminder of reminders) {
        props.reminders.push(reminder);
    }
}

/**
 * Loads reminder data from the list into the form.
 *
 * @param data
 */
function loadReminder(data) {
    reminder.value = {
        id: data.id ?? null,
        day: data.day ?? null,
        month: data.month ?? null,
        description: data.description ?? null,
        interval: data.interval ?? null,
    };
}

/**
 * Save form data via ajax call and reload the reminder list.
 */
function saveReminder() {
    errorMessage.value = null;
    if (reminder.value.id === null) {
        window.axios.post(
            route('calendar.reminder.add'),
            reminder.value
        ).then(response => {
            setReminders(response.data);
        }).catch(error => {
            console.error(error);
            errorMessage.value = `[${error.code}] ${error.message}`;
        });
    } else {
        window.axios.patch(
            route('calendar.reminder.update', [reminder.value.id]),
            reminder.value
        ).then(response => {
            setReminders(response.data);
        }).catch(error => {
            console.error(error);
            errorMessage.value = `[${error.code}] ${error.message}`;
        });
    }

    reminder.value = {
        id: null,
        day: null,
        month: null,
        description: null,
        interval: null,
    };
}

function deleteReminder(id) {
    errorMessage.value = null;
    window.axios.delete(
        route('calendar.reminder.delete', [id])
    ).then(response => {
        setReminders(response.data);
    }).catch(error => {
        console.error(error);
        errorMessage.value = `[${error.code}] ${error.message}`;
    });
}
</script>

<template>
    <Head title="Calendar" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Calendar
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="w-full">
                            <form v-on:submit.prevent="saveReminder">
                            <input type="hidden" v-model.number="reminder.id" />
                            <table class="table-auto w-full border-collapse border border-slate-500 text-left">
                                <thead>
                                <tr>
                                    <th colspan="2" class="p-1">Date (DD/MM)</th>
                                    <th class="p-1">Description</th>
                                    <th class="p-1">Reminder interval</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="p-1 w-[10%]"><input v-model.number="reminder.day" type="number" min="1" max="31" required class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 w-full" /></td>
                                    <td class="p-1 w-[10%]"><input v-model.number="reminder.month" type="number" min="1" max="12" required class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 w-full" /></td>
                                    <td class="p-1 w-[60%]"><input v-model="reminder.description" type="text" required class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 w-full" /></td>
                                    <td class="p-1 w-[20%]">
                                        <select v-model="reminder.interval" required class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1">
                                            <option v-for="option in reminderIntervals" :value="option.value">
                                                {{ option.text }}
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <div v-if="errorMessage" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                            <span class="font-medium">ERROR!</span> {{ errorMessage }}
                                        </div>
                                    </td>
                                    <td class="w-full text-right p-4">
                                        <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">Save</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            </form>
                        </div>
                        <br />
                        <div v-if="reminders.length >= 1" class="w-full">
                            <table class="table-auto w-full text-left">
                                <thead>
                                <tr>
                                    <th colspan="2">Date (DD/MM)</th>
                                    <th>Description</th>
                                    <th>Reminder interval</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="reminder in reminders">
                                    <td>{{ reminder.day }}</td>
                                    <td>{{ reminder.month }}</td>
                                    <td>{{ reminder.description }}</td>
                                    <td>{{ reminderIntervals.find(r => r.value === reminder.interval)?.text ?? 'undefined' }}</td>
                                    <td><a href="#" @click="loadReminder(reminder)">Edit</a> | <a href="#" @click="deleteReminder(reminder.id)">Delete</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
