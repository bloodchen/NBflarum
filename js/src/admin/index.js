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
});
