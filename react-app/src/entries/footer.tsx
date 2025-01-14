import { RootLoader } from "../common/root-loader";
import mountPoints from '../../../wordpress-theme/mount-points.json';

export default function Footer() {
    return 'Footer';
}

const id = mountPoints?.footer ?? 'react-footer';
RootLoader(id, Footer);