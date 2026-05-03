import QueueStatus from "@/layouts/queue/queue-status-layout"
import { QueueInfo } from "@/services/queue-service"

import '../../../css/queue/queue-page.css';

export default function Main(props: QueueInfo) {
    return <QueueStatus {...props} />
}