app.initializers.add('jeffchen-nbflarum', () => {
  console.log("hello admin")
  app.extensionData
    .for('jeffchen-nbflarum')
    .registerSetting(
      {
        setting: 'nbflarum.minTip',
        label: 'Minium Tip Amount',
        type: 'number',
        min:2,
        placeholder:2
      },
    )
    .registerSetting(
      {
        setting: 'nbflarum.contribution',
        label: '% of tips contributes to community fund',
        type: 'number',
        min:20,
        placeholder:20
      },
    )
    .registerSetting(
      {
        setting: 'nbflarum.help',
        label: 'Help URL',
        type: 'text',
        placeholder:"help URL"
      },
    )
    .registerSetting(
      {
        setting: 'nbflarum.node',
        label: 'NBnode URL(do not change if you do not understand)',
        type: 'text',
        placeholder: 'https://api.nbdomain.com/api/'
      },
    )
});
