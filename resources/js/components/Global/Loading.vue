<template>
  <v-container fluid style="position: absolute; width: 100%; height: 100%; left: 0; right: 0; z-index: 2; background-color: #FFF;">
    <v-row
      class="fill-height"
      align-content="center"
      justify="center"
    >
      <v-col
        class="text-subtitle-1 text-center"
        cols="12"
      >
        Loading
      </v-col>
      <v-col cols="6">
        <v-progress-linear
          color="green accent-4"
          indeterminate
          rounded
          height="6"
        ></v-progress-linear>
      </v-col>
    </v-row>
  </v-container fluid>
</template>