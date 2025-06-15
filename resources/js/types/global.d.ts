import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
        gtag?: (...args: any[]) => void
    }

    var route: typeof ziggyRoute;
}
