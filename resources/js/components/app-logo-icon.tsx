import { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#16a34a" {...props}>
            <path d="M2 20L10.5 4l1.5 3 1.5-3L22 20Z" fill="#16a34a" />
            <path d="M12 9.5L17.25 20H6.75L12 9.5Z" fill="#ffffff" />
        </svg>
    );
}
