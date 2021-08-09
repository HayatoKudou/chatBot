import React, { useRef, useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { useForm } from "react-hook-form";

const App = () => {

    const form = useRef(null)
    const { register, handleSubmit, reset, formState: { errors }} = useForm();
    const [sentiment, setSentiment] = useState('NEUTRAL');
    const [sentimentScore, setSentimentScore] = useState();
    const [chatMessage, setChatMessage] = useState([{ user: 'bot', message: 'はじめまして、結月ゆかりです。' }]);

    useEffect(() => {
        renderChatMessage(chatMessage);
    }, []);

    function onSubmit(e) {

        reset();
        let message = chatMessage;
        setChatMessage([...chatMessage, {
            user: 'user', message: e.message,
        }]);
        message.push({ user: 'user', message: e.message });

        fetch('/api/getSentiment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: e.message }),
        })
            .then(res => res.json())
            .then(objects => {
                setSentiment(objects.sentiment);
                setSentimentScore(objects.sentimentScore);
                setChatMessage([...chatMessage,
                { user: 'bot', message: objects.sentiment, }
                ]);
                message.push({ user: 'bot', message: objects.sentiment });
                renderChatMessage(message);
            })
            .catch(error => console.log(error));
    }

    function renderChatMessage(message) {
        let elms = [];
        console.log(message)
        message.map((val, index) => {
            elms.push(<div key={index} className={'arrow_box ' + val.user}>{val.message}</div>);
        })
        ReactDOM.render(elms, document.getElementById('chat_message'));

        const el = document.getElementById('chat_message');
        el.scrollTo(0, el.scrollHeight);
    }

    let imgPath;
    if(sentiment === 'NEUTRAL'){
        imgPath = "/storage/1.png";
    } else if(sentiment === 'POSITIVE'){
        imgPath = "/storage/4.png";
    } else if(sentiment === 'NEGATIVE'){
        imgPath = "/storage/9.png";
    } else {
        imgPath = "/storage/1.png";
    }

    return (
        <div style={{ display: 'flex' }}>
            <div className="chat_container">
                <div className="chat_message" id="chat_message"></div>
                <form ref={form} onSubmit={handleSubmit(onSubmit)} className="chatInputForm">
                    <input style={{width: '70%', height: '40px'}} {...register('message', { required: true })} autocomplete="off" />
                    {/* <input type="submit" /> */}
                </form>
            </div>
            <img className="bot_image" src={imgPath}></img>
        </div>
    );
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}