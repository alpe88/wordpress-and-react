import { RootLoader } from "../common/root-loader";
import mountPoints from '../../../wordpress-theme/mount-points.json';

export default function FrontPage() {

    return 'Hello from FrontPage'
}

const id = mountPoints?.front_page ?? 'react-front-page';
RootLoader(id, FrontPage);