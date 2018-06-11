const express = require('express');
const router = express.Router();
const rp = require('request-promise');

const BASE_URL = `http://api.localhost/`;

const TYPE_USER = `user`;
const TYPE_HISTORY = `history`;

/* GET users listing. */
router.get('/', function(req, res, next) {
    let getUrl = function(type, id) {
        return BASE_URL + "?type=" + type + "&id=" + id;
    };

    let jsonget = function(uri){
        var options = {
            uri: uri,
            transform2xxOnly: true, // ステータスコード200以外のときにHTMLページを帰す場合はtrueにする
            transform: function (body) {
                return JSON.parse(body);
            },
        };

        return rp(options);
    };

    let id = req.query.id;
    let userData = {},
        historyData = [];

    console.log(getUrl(TYPE_USER, id));
    let reqUserApi = jsonget(getUrl(TYPE_USER, id))
        .then(function (json) {
            userData = json;
        });

    let reqHistoryApi = jsonget(getUrl(TYPE_HISTORY, id))
        .then(function (json) {
            historyData = json;
        });

    Promise.all([
        reqUserApi,
        reqHistoryApi
    ])
    .then(function() {
        res.render(
            'history',
            {
                'username' : userData.name,
                'histories' : historyData
            }
        );
    });

});

module.exports = router;
