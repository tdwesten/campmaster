export interface Guest {
    id: string;
    first_name: string;
    last_name: string;
    email: string | null;
    phone: string | null;
    address: string | null;
    postal_code: string | null;
    city: string | null;
    country: string | null;
    date_of_birth: string | null;
    notes: string | null;
    tenant_id: string;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}
