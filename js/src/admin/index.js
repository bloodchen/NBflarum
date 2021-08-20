app.initializers.add('chen/nbdomain-login', () => {
  console.log("hello admin")
  app.extensionData
    .for('chen/nbdomain-login')
    .registerSetting(
      {
        setting: 'nbflarum.accessKeyId',
        label: 'accessKeyId',
        type: 'text',
      },
    )
});
