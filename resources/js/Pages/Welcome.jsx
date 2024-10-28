import { Head } from '@inertiajs/react';
import Header from '@/Components/Header';
import Hero from '@/Components/Hero';
import DownloadSection from '@/Components/DownloadSection';
import StatsCounter from '@/Components/StatsCounter';
import Entreprises from '@/Components/Entreprises';
import Fonctionnalites from '@/Components/Fonctionnalites';
import Footer from '@/Components/Footer';

export default function Welcome({ auth }) {
    return (
        <div className='w-full'>
            <Head title="Welcome" />
            <Header auth={auth} />
            <Hero/>
            <DownloadSection/>
            <StatsCounter/>
            <Entreprises/>
            <Fonctionnalites/>
            <Footer/>
        </div>
    );
}
