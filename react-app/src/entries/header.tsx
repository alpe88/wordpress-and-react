import { RootLoader } from "../common/root-loader";
import mountPoints from '../../../wordpress-theme/mount-points.json';

export default function Header(){
    return <h1>YOLO DOLO</h1>;
}

const id = mountPoints?.header ?? 'react-header';
RootLoader(id, Header);