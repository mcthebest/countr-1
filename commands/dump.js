module.exports = {
  description: "Dump a server's data to DMs.",
  usage: {
    "[server id]": "Normally it will dump the current server's data. If you supply this, it will dump that server's data instead."
  },
  examples: {},
  aliases: [],
  permissionRequired: 4, // 0 All, 1 Mods, 2 Admins, 3 Server Owner, 4 Bot Admin, 5 Bot Owner
  checkArgs: (args) => args.length <= 1
}

module.exports.run = async function(client, message, args, config, gdb) {
  let code = args.join(" ");
  try {
    let evaled = eval(code);
    if (typeof evaled !== "string") evaled = require("util").inspect(evaled);

    message.channel.send("🆗 Evaluated successfully.\n```js\n" + evaled + "```")
  } catch(e) {
    if (typeof(e) == "string") e = e.replace(/`/g, "`" + String.fromCharCode(8203)).replace(/@/g, "@" + String.fromCharCode(8203))
    message.channel.send("🆘 JavaScript failed.\n```js" + e + "```")
  }
}