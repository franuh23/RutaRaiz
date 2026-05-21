import React from 'react';
import Container from '../components/layout/Container';
import RegisterForm from '../components/auth/RegisterForm';

export default function RegisterPage() {
  return (
    <Container>
      <div className="d-flex justify-content-center align-items-center" style={{ minHeight: '75vh', padding: '1rem 0' }}>
        <RegisterForm />
      </div>
    </Container>
  );
}