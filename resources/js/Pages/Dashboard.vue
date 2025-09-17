<template>
  <div class="p-6">
    <Card class="w-[350px]">
      <CardHeader>
        <CardTitle>Halo dari shadcn-vue 🎉</CardTitle>
        <CardDescription>Integrasi berhasil!</CardDescription>
      </CardHeader>
      <CardContent>
        <Button @click="alert('Mantap!')">Klik Aku</Button>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { Button, Card, CardHeader, CardTitle, CardDescription, CardContent } from "shadcn-vue"
</script>
