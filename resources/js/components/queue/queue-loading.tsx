
import '../../../css/queue/queue-loading.css';

export default function QueueLoading() {
    return (
        <>
        <div className='loading-line-box'>
                <div className='loading-line'></div>
        </div>

            <div className='loading-box'>
                <div className="loading-element loading-element-a"></div>
                <div className="loading-element loading-element-b"></div>
            </div>
        </>
    );
}