import { useEffect, useState } from "react";
import { QueueInfo, heartbeat, transferToTarget } from "@/services/queue-service";

import '../../../css/queue/queue-position.css';

/**
 * This component will update the information automatically
 */
export function QueuePosition(props: QueueInfo) {
    const [currentQueueInfo, updateQueueInfo] = useState(props);

    useEffect(
        () => {
            const id = setTimeout(
                () => {
                    heartbeat().then(
                        newInfo => {
                            if (newInfo.position === 0) {
                                transferToTarget();
                                return;
                            }

                            updateQueueInfo(newInfo);
                        }
                    ).catch(
                        (error) => {
                            console.error(error);
                            setTimeout(
                                () => {
                                    window.location.href = window.location.href;
                                },
                                currentQueueInfo.updateMileseconds
                            );
                        }
                    );
                },
                currentQueueInfo.updateMileseconds
            )
            return () => {
                clearTimeout(id);
            }
        },
        [currentQueueInfo]
    );

    return (
        <p className="queue-position" key={currentQueueInfo.position}>
            {currentQueueInfo.position}°
        </p>
    );
}