import React from 'react';
import { useForm, Head, router }  from '@inertiajs/react';
import { Form, Button } from 'react-bootstrap';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Show({ auth, user }) {
    const returnCallback = () => {
        router.visit(route('users.index'));
    }

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="User Details" />
            <div className="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
                <h3 className="font-bold">User Profile: { user.first_name + ' ' + user.last_name }</h3>
                <hr />
                <Form>
                    <div className="flex space-x-4">
                        <Form.Group controlId="first_name" className="mb-3 flex-grow">
                            <Form.Label className="font-bold">First Name</Form.Label>
                            <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                                {user.first_name}
                            </div>
                        </Form.Group>

                        <Form.Group controlId="last_name" className="mb-3 flex-grow">
                            <Form.Label className="font-bold">Last Name</Form.Label>
                            <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                                {user.last_name}
                            </div>
                        </Form.Group>
                    </div>
                    <Form.Group controlId="email" className="mb-3">
                        <Form.Label className="font-bold">Email</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.email}
                        </div>
                    </Form.Group>
                    <Form.Group controlId="mobile_number" className="mb-3">
                        <Form.Label className="font-bold">Mobile Number</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.mobile_number}
                        </div>
                    </Form.Group>
                    <Form.Group controlId="address" className="mb-3">
                        <Form.Label className="font-bold">Address</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.address}
                        </div>
                    </Form.Group>
                    <Form.Group controlId="city" className="mb-3">
                        <Form.Label className="font-bold">City</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.city}
                        </div>
                    </Form.Group>
                    <Form.Group controlId="state" className="mb-3">
                        <Form.Label className="font-bold">State / Province</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.state}
                        </div>
                    </Form.Group>
                    <Form.Group controlId="zip" className="mb-3">
                        <Form.Label className="font-bold">Postal Code</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.zip}
                        </div>
                    </Form.Group>
                    <Form.Group controlId="country" className="mb-3">
                        <Form.Label className="font-bold">Country</Form.Label>
                        <div className="mt-1 text-base text-gray-900 sm:mt-0 sm:col-span-2">
                            {user.country}
                        </div>
                    </Form.Group>
                    {/* todo - add timestamps */}
                    <Button variant="secondary" onClick={returnCallback}>
                        Return
                    </Button>
                </Form>
            </div>

        </AuthenticatedLayout>
    );
}