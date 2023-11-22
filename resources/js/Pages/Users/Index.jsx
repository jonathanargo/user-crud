import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm, Head } from '@inertiajs/react';

export default function Index({ auth, users }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Users" />
            <div className="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                <p>Hello World</p>
            </div>
        </AuthenticatedLayout>
    );
}