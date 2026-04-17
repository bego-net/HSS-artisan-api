import { useEffect, useState } from 'react';
import axios from 'axios';

import type { Service } from '../types/service';

const api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL ?? 'http://localhost:8000/api',
});

interface ServicesResponse {
    data: Service[];
}

export default function ServiceList() {
    const [services, setServices] = useState<Service[]>([]);
    const [loading, setLoading] = useState<boolean>(true);

    useEffect(() => {
        const fetchServices = async () => {
            try {
                const response = await api.get<Service[] | ServicesResponse>('/services');
                const data = Array.isArray(response.data) ? response.data : response.data.data;

                setServices(data);
                console.log(data);
            } catch (error) {
                console.error(error);
            } finally {
                setLoading(false);
            }
        };

        fetchServices();
    }, []);

    if (loading) {
        return <div>Loading...</div>;
    }

    return (
        <div style={{ padding: '16px' }}>
            {services.map((service) => (
                <div
                    key={service.id}
                    style={{
                        border: '1px solid #ccc',
                        borderRadius: '8px',
                        padding: '16px',
                        marginBottom: '12px',
                    }}
                >
                    <div style={{ marginBottom: '8px', fontWeight: 'bold' }}>{service.title}</div>
                    <div style={{ marginBottom: '8px' }}>{service.description}</div>
                    <div>{service.icon}</div>
                </div>
            ))}
        </div>
    );
}
