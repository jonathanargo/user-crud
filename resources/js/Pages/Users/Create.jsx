
import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Form, Button } from 'react-bootstrap';
import { useForm, Head } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import { Alert } from 'react-bootstrap';

export default function Create({ auth }) {

    const { data, setData, post, patch, processing, errors, clearErrors } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        mobile_number: '',
        address: '',
        city: '',
        state: '',
        zip: '',
        country: ''
    });

    const [showError, setShowError] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setData({ ...data, [name]: value });
    };

    const submitCallback = (e) => {
        e.preventDefault;
        console.log('Submitting form');
        post(route('users.store'), {
            onSuccess: () => {
                console.log('Success');
            },
            onError: (e) => {
                console.log('Error', e);
                setShowError(true);
            }
        });
    };

    const closeCallback = () => {
        router.visit(route('users.index'));
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Create User" />
            <div className="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
                <h1 className="font-bold">Profile Info</h1>
                <p>Manage your personal information, timezone, and profile image.</p>
                {
                    showError &&
                    <Alert variant="danger" onClose={() => setShowError(false)} dismissible>
                        {Object.values(errors).flat().map((error, index) => (
                            <p key={index}>{error}</p>
                        ))}
                    </Alert>
                }
                <Form>
                    <div className="flex space-x-4">
                        <Form.Group controlId="first_name" className="mb-3 flex-grow">
                            <Form.Label>First Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="first_name"
                                value={data.first_name}
                                onChange={handleChange}
                            />
                            <InputError message={errors['first_name']} className="mt-2" />
                        </Form.Group>

                        <Form.Group controlId="last_name" className="mb-3 flex-grow">
                            <Form.Label>Last Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="last_name"
                                value={data.last_name}
                                onChange={handleChange}
                            />
                            <InputError message={errors['last_name']} className="mt-2" />
                        </Form.Group>
                    </div>

                    <Form.Group controlId="email" className="mb-3">
                        <Form.Label>Email</Form.Label>
                        <Form.Control
                            type="email"
                            name="email"
                            value={data.email}
                            onChange={handleChange}
                        />
                        <InputError message={errors['email']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="mobile_number" className="mb-3">
                        <Form.Label>Mobile Number</Form.Label>
                        <Form.Control
                            type="text"
                            name="mobile_number"
                            value={data.mobile_number}
                            onChange={handleChange}
                        />
                        <InputError message={errors['mobile_number']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="address" className="mb-3">
                        <Form.Label>Address</Form.Label>
                        <Form.Control
                            type="text"
                            name="address"
                            value={data.address}
                            onChange={handleChange}
                        />
                        <InputError message={errors['address']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="city" className="mb-3">
                        <Form.Label>City</Form.Label>
                        <Form.Control
                            type="text"
                            name="city"
                            value={data.city}
                            onChange={handleChange}
                        />
                        <InputError message={errors['city']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="state" className="mb-3">
                        <Form.Label>State</Form.Label>
                        <Form.Control
                            type="text"
                            name="state"
                            value={data.state}
                            onChange={handleChange}
                        />
                        <InputError message={errors['state']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="zip" className="mb-3">
                        <Form.Label>Postal Code</Form.Label>
                        <Form.Control
                            type="text"
                            name="zip"
                            value={data.zip}
                            onChange={handleChange}
                        />
                        <InputError message={errors['zip']} className="mt-2" />
                    </Form.Group>

                    { /* TODO JSA - Change to drop down */}
                    <Form.Group controlId="country" className="mb-3">
                        <Form.Label>Country</Form.Label>
                        <Form.Control
                            type="text"
                            name="country"
                            value={data.country}
                            onChange={handleChange}
                        />
                        <InputError message={errors['country']} className="mt-2" />
                    </Form.Group>
                    <hr></hr>

                    <Button variant="primary" onClick={submitCallback} disabled={processing}>
                        Save
                    </Button>
                    <Button variant="secondary" onClick={closeCallback} disabled={processing} className="ml-3">Cancel</Button>
                </Form>
            </div>
        </AuthenticatedLayout>
    );
};
