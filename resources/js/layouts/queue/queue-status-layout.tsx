
import QueueLoading from "@/components/queue/queue-loading";
import { QueuePosition } from "@/components/queue/queue-position"
import { getTargetPageName, QueueInfo } from "@/services/queue-service"

import '../../../css/queue/queue-status.css';


export default function QueueStatus(props: QueueInfo) {
    return <div className="queue-status">
        <h1 className="queue-status-title">You are in queue</h1>
        <p className="queue-info-text">Your queue position:</p>
        <QueuePosition {...props}></QueuePosition>
        <QueueLoading></QueueLoading>
        <p className="queue-info-text">You'll be redirected to <span className="queue-target-name">{getTargetPageName()}</span> automatically when some space be found.</p>
    </div>
}