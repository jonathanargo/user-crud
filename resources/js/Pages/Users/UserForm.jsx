
import React, { useState, useEffect, useRef } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Form, Button } from 'react-bootstrap';
import { useForm, Head, router } from '@inertiajs/react';
import InputError from '@/Components/InputError';
import { Alert } from 'react-bootstrap';
import { getCodeList } from 'country-list';
import states from 'states-us';
import InputMask from 'react-input-mask';
import { GoogleMap, useJsApiLoader, Autocomplete } from '@react-google-maps/api';


const libraries = ['places'];
const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;

export default function UserForm({ auth, user, mode = 'create', showErrorAlert = true}) {

    const { data, setData, post, patch, processing, errors, clearErrors } = useForm({
        id: '',
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

    useEffect(() => {
        if (mode === 'edit') {
            setData(user);
        }
    }, [mode, user]);


    const [showError, setShowError] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setData({ ...data, [name]: value });
    };

    const submitCallback = (e) => {
        e.preventDefault;
        clearErrors();
        if (mode === 'create') {
            post(route('users.store'), {
                onError: (e) => {
                    setShowError(true);
                }
            });
        } else {
            patch(route('users.update', user.id), {
                onError: (e) => {
                    console.error(e);
                    setShowError(true);
                }
            });
        }
    };

    const closeCallback = () => {
        router.visit(route('users.index'));
    };

    const { isLoaded } = useJsApiLoader({
        id: 'google-map-script',
        googleMapsApiKey: apiKey,
        libraries: libraries
    });

    const autoCompleteRef = useRef(null);

    // Create a ref to data so that we can access it inside useEffect below
    const dataRef = useRef(data);
    useEffect(() => {
        dataRef.current = data;
    }, [data]);

    useEffect(() => {
        if (isLoaded) {
            const autocomplete = new window.google.maps.places.Autocomplete(autoCompleteRef.current);
            autocomplete.addListener("place_changed", () => {
                const selectedPlace = autocomplete.getPlace();
                
                // Get the address fields for the selected place
                let streetNumber = selectedPlace.address_components.find(component => component.types.includes('street_number')).long_name;
                let streetName = selectedPlace.address_components.find(component => component.types.includes('route')).long_name;
                let city = selectedPlace.address_components.find(component => component.types.includes('locality')).long_name;
                let state = selectedPlace.address_components.find(component => component.types.includes('administrative_area_level_1')).short_name;
                let zip = selectedPlace.address_components.find(component => component.types.includes('postal_code')).long_name;
                let country = selectedPlace.address_components.find(component => component.types.includes('country')).short_name;

                // Populate them, but use the dataRef so that we don't wipe out the existing fields.
                setData({ ...dataRef.current,
                    address: streetNumber + ' ' + streetName,
                    city: city,
                    state: state,
                    zip: zip,
                    country: country.toLowerCase()
                });
            });
        }
    }, [isLoaded]);

    let title = (mode === 'create' ? 'Create User' : 'Update User');

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={title}>
            </Head>
            <div className="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
                <h1 className="font-bold">Profile Info</h1>
                <p>Manage your personal information, timezone, and profile image.</p>
                {
                    showError && showErrorAlert &&
                    <Alert variant="danger" onClose={() => setShowError(false)} dismissible>
                        {Object.values(errors).flat().map((error, index) => (
                            <p key={index}>{error}</p>
                        ))}
                    </Alert>
                }
                <Form>
                    {
                        mode === 'edit' &&
                        <Form.Group controlId="id">
                            <Form.Control type="hidden" name="id" value={data.id} />
                        </Form.Group>
                    }
                    <div className="flex space-x-4">

                        <Form.Group controlId="first_name" className="mb-3 flex-grow">
                            <Form.Label>First Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="first_name"
                                value={data.first_name ?? ''}
                                onChange={handleChange}
                            />
                            <InputError message={errors['first_name']} className="mt-2" />
                        </Form.Group>

                        <Form.Group controlId="last_name" className="mb-3 flex-grow">
                            <Form.Label>Last Name</Form.Label>
                            <Form.Control
                                type="text"
                                name="last_name"
                                value={data.last_name ?? ''}
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
                            value={data.email ?? ''}
                            onChange={handleChange}
                        />
                        <InputError message={errors['email']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="mobile_number" className="mb-3">
                        <Form.Label>Mobile Number</Form.Label>
                        <InputMask
                            mask="999-999-9999"
                            maskChar="_"
                            value={data.mobile_number ?? ''}
                            onChange={handleChange}
                        >
                            {() => <Form.Control type="text" name="mobile_number" />}
                        </InputMask>
                        <InputError message={errors['mobile_number']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="address" className="mb-3">
                        <Form.Label>Address</Form.Label>
                        <Form.Control
                            type="text"
                            name="address"
                            value={data.address ?? ''}
                            onChange={handleChange}
                            ref={autoCompleteRef}
                        />
                        <InputError message={errors['address']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="city" className="mb-3">
                        <Form.Label>City</Form.Label>
                        <Form.Control
                            type="text"
                            name="city"
                            value={data.city ?? ''}
                            onChange={handleChange}
                        />
                        <InputError message={errors['city']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="state" className="mb-3">
                        <Form.Label>State / Province</Form.Label>
                        <Form.Control
                            type="text"
                            name="state"
                            value={data.state ?? ''}
                            onChange={handleChange}
                        />
                        <InputError message={errors['state']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="zip" className="mb-3">
                        <Form.Label>Postal Code</Form.Label>
                        <Form.Control
                            type="text"
                            name="zip"
                            value={data.zip ?? ''}
                            onChange={handleChange}
                        />
                        <InputError message={errors['zip']} className="mt-2" />
                    </Form.Group>

                    <Form.Group controlId="country" className="mb-3">
                        <Form.Label>Country</Form.Label>
                        <Form.Select name="country" value={data.country ?? ''} onChange={handleChange}>
                            <option key="" value=""></option>
                            {Object.entries(getCodeList()).map(([code, name], index) => (
                                <option key={index} value={code}>
                                    {name}
                                </option>
                            ))}
                        </Form.Select>
                        <InputError message={errors['country']} className="mt-2" />
                    </Form.Group>
                    <hr />

                    <Button variant="primary" onClick={submitCallback} disabled={processing}>
                        Save
                    </Button>
                    <Button variant="secondary" onClick={closeCallback} disabled={processing} className="ml-3">Cancel</Button>
                </Form>
            </div>
        </AuthenticatedLayout>
    );
};
