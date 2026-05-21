import React from 'react';
import Container from '../components/layout/Container';
import LoginForm from '../components/auth/LoginForm';

export default function LoginPage() {
  return (
    <Container>
      <div className="d-flex justify-content-center align-items-center" style={{ minHeight: '65vh', padding: '2rem 0' }}>
        <LoginForm />
      </div>
    </Container>
  );
}