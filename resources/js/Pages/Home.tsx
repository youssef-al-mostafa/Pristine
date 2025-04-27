import HeroSection from '@/Components/app/HeroSection';
import Footer from '@/Components/navigation/Footer';
import Navbar from '@/Components/navigation/Navbar';
import { Head } from '@inertiajs/react';

export default function Home() {
    return (
        <>
            <Head title="Home" />
            <Navbar/>
            <HeroSection/>
            <Footer/>
        </>
    );
}
